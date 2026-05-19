<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantPortalOtpMail;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Program;
use App\Models\SystemSetting;
use App\Support\PublicCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicantPortalController extends Controller
{
    private const OTP_TTL_SECONDS = 1800;
    private const SESSION_TTL_SECONDS = 3600;

    private const EDITABLE_STATUSES = ['pending', 'submitted', 'pending_docs', 'for_interview', 'approved', 'accepted'];

    public function requestOtp(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $application = $this->findApplication($validated['reference_number'], $validated['email']);

        if ($application) {
            $otp = (string) random_int(100000, 999999);

            if (SystemSetting::get('email_notifications', '1') !== '0') {
                try {
                    Mail::to($application->email)->send(new ApplicantPortalOtpMail($application, $otp));
                } catch (\Throwable $e) {
                    Log::error('Failed to send applicant portal OTP: '.$e->getMessage());
                }
            }

            $this->portalCache()->put($this->otpCacheKey($application), hash('sha256', $otp), self::OTP_TTL_SECONDS);
        }

        return response()->json([
            'message' => 'If the reference number and email match an application, a verification code has been sent.',
            'expires_in_seconds' => self::OTP_TTL_SECONDS,
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'otp' => ['required', 'digits:6'],
        ]);

        $application = $this->findApplication($validated['reference_number'], $validated['email']);
        $storedHash = $application ? $this->portalCache()->get($this->otpCacheKey($application)) : null;

        if (!$application || !$storedHash || !hash_equals((string) $storedHash, hash('sha256', $validated['otp']))) {
            throw ValidationException::withMessages([
                'otp' => ['The verification code is invalid or expired.'],
            ]);
        }

        $this->portalCache()->forget($this->otpCacheKey($application));

        $token = Str::random(64);
        $this->portalCache()->put($this->sessionCacheKey($token), $application->id, self::SESSION_TTL_SECONDS);

        return response()->json([
            'message' => 'Applicant portal verified.',
            'portal_token' => $token,
            'data' => $this->payload($application->fresh(['program'])),
        ]);
    }

    public function show(Request $request)
    {
        $application = $this->applicationFromToken($request);

        return response()->json([
            'data' => $this->payload($application->load('program')),
        ]);
    }

    public function update(Request $request)
    {
        $application = $this->applicationFromToken($request);
        $this->ensureEditable($application);

        $validated = $request->validate($this->updateRules());
        $protected = array_flip([
            'id', 'reference_number', 'status', 'admin_notes', 'submitted_at',
            'document_upload_token', 'program_id', 'created_at', 'updated_at',
        ]);
        $data = array_diff_key($validated, $protected);

        DB::transaction(function () use ($application, &$data) {
            $this->applyProgramChoiceChanges($application, $data);

            $application->fill($data);
            $application->last_edited_at = now();
            $application->last_edited_by = 'applicant';
            $application->edit_count = (int) ($application->edit_count ?? 0) + 1;
            $application->save();
        });

        PublicCache::clear();

        return response()->json([
            'message' => 'Application information updated.',
            'data' => $this->payload($application->fresh(['program'])),
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $application = $this->applicationFromToken($request);
        $this->ensureEditable($application);

        $validated = $request->validate([
            'document_type' => ['required', 'string', Rule::in(array_keys($this->documentColumnMap()))],
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,webp,pdf',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
            ],
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $path = $file->storeAs('applications/'.$application->id, Str::uuid().'.'.$extension, 'supabase');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $supabase */
        $supabase = Storage::disk('supabase');
        $url = $supabase->url($path);
        $column = $this->documentColumnMap()[$validated['document_type']];

        $application->update([
            $column => $url,
            'last_edited_at' => now(),
            'last_edited_by' => 'applicant',
            'edit_count' => (int) ($application->edit_count ?? 0) + 1,
        ]);

        return response()->json([
            'message' => 'Document uploaded.',
            'data' => $this->payload($application->fresh(['program'])),
        ]);
    }

    private function updateRules(): array
    {
        return [
            'applicant_type' => ['nullable', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'string', 'max:50'],
            'civil_status' => ['nullable', 'string', 'max:50'],
            'religion' => ['nullable', 'string', 'max:100'],
            'citizenship' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'permanent_address' => ['nullable', 'string', 'max:2000'],
            'present_address' => ['nullable', 'string', 'max:2000'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_suffix' => ['nullable', 'string', 'max:50'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'father_contact' => ['nullable', 'string', 'max:30'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'mother_contact' => ['nullable', 'string', 'max:30'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'guardian_contact' => ['nullable', 'string', 'max:30'],
            'elementary_school' => ['nullable', 'string', 'max:255'],
            'elementary_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'junior_high_school' => ['nullable', 'string', 'max:255'],
            'junior_high_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'senior_high_school' => ['nullable', 'string', 'max:255'],
            'senior_high_strand' => ['nullable', 'string', 'max:255'],
            'senior_high_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'previous_college' => ['nullable', 'string', 'max:255'],
            'previous_college_program' => ['nullable', 'string', 'max:255'],
            'previous_college_year_last_attended' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'scholarship_type' => ['nullable', 'string', 'max:255'],
            'scholarship_name' => ['nullable', 'string', 'max:255'],
            'health_conditions' => ['nullable', 'string', 'max:2000'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'max:30'],
            'gwa_grade_11' => ['nullable', 'numeric', 'min:60', 'max:100'],
            'gwa_grade_12' => ['nullable', 'numeric', 'min:60', 'max:100'],
            'first_choice' => ['nullable', 'string', 'max:255'],
            'second_choice' => ['nullable', 'string', 'max:255'],
            'pwd' => ['nullable', 'string', 'max:20'],
            'solo_parent' => ['nullable', 'string', 'max:20'],
            'indigenous' => ['nullable', 'string', 'max:20'],
            'four_ps' => ['nullable', 'string', 'max:20'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function applyProgramChoiceChanges(Application $application, array &$data): void
    {
        if (!array_key_exists('first_choice', $data) && !array_key_exists('second_choice', $data)) {
            return;
        }

        $currentFirst = (string) ($application->first_choice ?? '');
        $newFirstName = trim((string) ($data['first_choice'] ?? $currentFirst));
        $newSecondName = trim((string) ($data['second_choice'] ?? $application->second_choice ?? ''));

        if ($newFirstName !== '') {
            $newFirst = Program::where('name', $newFirstName)->lockForUpdate()->first();
            if (!$newFirst || !$newFirst->is_active || ((int) $newFirst->slots_left <= 0 && $newFirstName !== $currentFirst)) {
                throw ValidationException::withMessages([
                    'first_choice' => ['Selected program is already full or disabled. Please choose another program.'],
                ]);
            }
        }

        if ($newSecondName !== '') {
            if ($newSecondName === $newFirstName) {
                throw ValidationException::withMessages([
                    'second_choice' => ['First choice and second choice must be different.'],
                ]);
            }

            $newSecond = Program::where('name', $newSecondName)->first();
            if (!$newSecond || !$newSecond->is_active || (int) $newSecond->slots_left <= 0) {
                throw ValidationException::withMessages([
                    'second_choice' => ['Selected program is already full or disabled. Please choose another program.'],
                ]);
            }

            if ($newSecond->has_board_exam) {
                throw ValidationException::withMessages([
                    'second_choice' => ['Second choice must be a non-board-exam program. Please choose another program.'],
                ]);
            }
        }

        if ($newFirstName !== '' && $newFirstName !== $currentFirst) {
            $oldProgram = $application->program_id
                ? Program::whereKey($application->program_id)->lockForUpdate()->first()
                : Program::where('name', $currentFirst)->lockForUpdate()->first();
            $newProgram = Program::where('name', $newFirstName)->lockForUpdate()->first();

            if (!$newProgram || !$newProgram->is_active || (int) $newProgram->slots_left <= 0) {
                throw ValidationException::withMessages([
                    'first_choice' => ['Selected program is already full or disabled. Please choose another program.'],
                ]);
            }

            if ($oldProgram) {
                $oldProgram->increment('slots_left');
                if (!$oldProgram->is_active && (int) $oldProgram->slots_left > 0) {
                    $oldProgram->update(['is_active' => $this->databaseBoolean(true)]);
                }
            }

            $newSlotsLeft = max(0, (int) $newProgram->slots_left - 1);
            $newProgram->update([
                'slots_left' => $newSlotsLeft,
                'is_active' => $this->databaseBoolean($newSlotsLeft > 0),
            ]);

            $data['program_id'] = $newProgram->id;
        }
    }

    private function payload(Application $application): array
    {
        $interview = Interview::with('program')
            ->where('application_id', $application->id)
            ->latest()
            ->first();

        return [
            'application' => $application->makeHidden('document_upload_token')->toArray(),
            'editable' => in_array($application->status, self::EDITABLE_STATUSES, true),
            'documents' => $this->documentPayload($application),
            'interview' => $interview ? [
                'interview_date' => $interview->interview_date,
                'interview_time' => $interview->interview_time,
                'status' => $interview->status,
                'display_status' => $this->displayInterviewStatus($interview),
                'program' => $interview->program?->name,
            ] : null,
            'programs' => Program::orderBy('name')->get(['id', 'code', 'name', 'department', 'slots_left', 'is_active', 'has_board_exam']),
        ];
    }

    private function displayInterviewStatus(Interview $interview): string
    {
        $status = strtolower(trim((string) $interview->status));

        if (in_array($status, ['scheduled', 'interview scheduled'], true)) {
            return 'Scheduled';
        }

        if ($status === 'pending' && ($interview->interview_date || $interview->interview_time)) {
            return 'Scheduled';
        }

        if (in_array($status, ['completed', 'done'], true)) {
            return 'Completed';
        }

        if (in_array($status, ['cancelled', 'canceled'], true)) {
            return 'Cancelled';
        }

        if (in_array($status, ['no show', 'no_show', 'noshow', 'no-show'], true)) {
            return 'No Show';
        }

        return $interview->status ?: 'Pending';
    }

    private function documentPayload(Application $application): array
    {
        $labels = [
            'photo' => ['label' => 'ID Photo', 'column' => 'photo_path'],
            'birth_certificate' => ['label' => 'Birth Certificate', 'column' => 'birth_certificate_path'],
            'report_card' => ['label' => 'Report Card', 'column' => 'report_card_path'],
            'good_moral' => ['label' => 'Good Moral Certificate', 'column' => 'good_moral_path'],
            'tor' => ['label' => 'Transcript of Records', 'column' => 'tor_path'],
            'diploma' => ['label' => 'Diploma', 'column' => 'diploma_path'],
        ];

        return collect($labels)->map(function ($item, $type) use ($application) {
            $url = $application->{$item['column']};

            return [
                'type' => $type,
                'label' => $item['label'],
                'uploaded' => filled($url),
                'url' => $url,
            ];
        })->values()->all();
    }

    private function documentColumnMap(): array
    {
        return [
            'photo' => 'photo_path',
            'birth_certificate' => 'birth_certificate_path',
            'report_card' => 'report_card_path',
            'good_moral' => 'good_moral_path',
            'tor' => 'tor_path',
            'diploma' => 'diploma_path',
        ];
    }

    private function ensureEditable(Application $application): void
    {
        if (!in_array($application->status, self::EDITABLE_STATUSES, true)) {
            abort(423, 'Your application is currently under review. Editing is locked while the admissions office processes your application.');
        }
    }

    private function applicationFromToken(Request $request): Application
    {
        $token = (string) $request->bearerToken();
        if ($token === '') {
            $token = (string) $request->input('portal_token', $request->query('portal_token', ''));
        }

        $applicationId = $token !== '' ? $this->portalCache()->get($this->sessionCacheKey($token)) : null;
        if (!$applicationId) {
            abort(401, 'Applicant portal session expired. Please verify again.');
        }

        $this->portalCache()->put($this->sessionCacheKey($token), $applicationId, self::SESSION_TTL_SECONDS);

        return Application::with('program')->findOrFail($applicationId);
    }

    private function findApplication(string $referenceNumber, string $email): ?Application
    {
        return Application::whereRaw('LOWER(reference_number) = ?', [Str::lower(trim($referenceNumber))])
            ->whereRaw('LOWER(email) = ?', [Str::lower(trim($email))])
            ->first();
    }

    private function otpCacheKey(Application $application): string
    {
        return 'applicant_portal_otp:'.$application->id;
    }

    private function portalCache(): \Illuminate\Contracts\Cache\Repository
    {
        return Cache::store('database');
    }

    private function sessionCacheKey(string $token): string
    {
        return 'applicant_portal_session:'.hash('sha256', $token);
    }

    private function databaseBoolean(bool $value): bool|\Illuminate\Contracts\Database\Query\Expression
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return DB::raw($value ? 'true' : 'false');
        }

        return $value;
    }
}
