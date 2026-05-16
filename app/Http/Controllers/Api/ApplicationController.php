<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Application;
use App\Models\Program;
use App\Models\SystemSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\ApplicationSubmitted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;


class ApplicationController extends Controller
{
    public function submitPublic(Request $request)
    {
        $data = $request->validate([
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
            'elementary_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'junior_high_school' => ['nullable', 'string', 'max:255'],
            'junior_high_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'senior_high_school' => ['nullable', 'string', 'max:255'],
            'senior_high_strand' => ['nullable', 'string', 'max:255'],
            'senior_high_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'previous_college' => ['nullable', 'string', 'max:255'],
            'previous_college_program' => ['nullable', 'string', 'max:255'],
            'previous_college_year_last_attended' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
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
        ]);

        $uploadToken = Str::random(64);
        $data['reference_number'] = $this->generateReferenceNumber();
        $data['document_upload_token'] = hash('sha256', $uploadToken);
        $data['status'] = 'pending';
        $data['submitted_at'] = now();

        // Check if system is accepting applications
        if (SystemSetting::get('accept_applications', '1') === '0') {
            return response()->json([
                'message' => 'The application portal is currently closed. Please check back later.'
            ], 403);
        }

        $application = DB::transaction(function () use ($data) {
            // First choice must be available for public submission.
            if (!empty($data['first_choice'])) {
                $program = Program::where('name', $data['first_choice'])
                    ->lockForUpdate()
                    ->first();

                if (!$program || !$program->is_active || (int) ($program->slots_left ?? 0) <= 0) {
                    throw ValidationException::withMessages([
                        'first_choice' => ['Selected program is already full or disabled. Please choose another program.'],
                    ]);
                }

                $data['program_id'] = $program->id;

                // Decrease slots and auto-disable once full.
                $newSlotsLeft = max(0, (int) $program->slots_left - 1);
                $program->update([
                    'slots_left' => $newSlotsLeft,
                    'is_active' => $this->databaseBoolean($newSlotsLeft > 0),
                ]);
            }

            if (!empty($data['second_choice'])) {
                $secondChoice = Program::where('name', $data['second_choice'])->first();
                if (!$secondChoice || !$secondChoice->is_active || (int) ($secondChoice->slots_left ?? 0) <= 0) {
                    throw ValidationException::withMessages([
                        'second_choice' => ['Selected second choice program is already full or disabled. Please choose another program.'],
                    ]);
                }
            }

            return Application::create($data);
        });

        // Clear welcome page cache to reflect slot changes immediately
        Cache::forget('welcome_page_data');

        $payload = $application->toArray();
        unset($payload['document_upload_token']);
        $payload['upload_token'] = $uploadToken;

        // Keep the public submit response fast; email can finish after the browser
        // already has the reference number and can show the success modal.
        if ($application->email && SystemSetting::get('email_notifications', '1') !== '0') {
            app()->terminating(function () use ($application) {
                try {
                    Mail::to($application->email)->send(new ApplicationSubmitted($application));
                } catch (\Exception $e) {
                    Log::error('Failed to send application submission email: ' . $e->getMessage());
                }
            });
        }

        return response()->json(['data' => $payload], 201);
    }

    public function uploadDocument(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        
        $validated = $request->validate([
            'document_type' => ['required', 'string', Rule::in([
                'id_photo',
                'photo',
                'birth_certificate',
                'report_card',
                'good_moral',
                'tor',
                'diploma',
                'other',
            ])],
            'upload_token' => ['nullable', 'string', 'size:64'],
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,webp,pdf',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
            ],
        ]);

        if (! $this->validUploadToken($application, (string) ($validated['upload_token'] ?? ''))) {
            abort(403, 'Invalid or missing document upload token.');
        }

        $type = $validated['document_type'];
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $path = $file->storeAs('applications/'.$id, Str::uuid().'.'.$extension, 'supabase');
        
        /** @var \Illuminate\Filesystem\FilesystemAdapter $supabase */
        $supabase = Storage::disk('supabase');
        $url = $supabase->url($path);

        // Map document_type to column name
        $columnMap = [
            'id_photo'          => 'photo_path',
            'photo'             => 'photo_path',
            'birth_certificate' => 'birth_certificate_path',
            'report_card'       => 'report_card_path',
            'good_moral'        => 'good_moral_path',
            'tor'               => 'tor_path',
            'diploma'           => 'diploma_path',
        ];

        if (array_key_exists($type, $columnMap)) {
            $application->update([$columnMap[$type] => $url]);
        }

        return response()->json(['data' => $application->makeHidden('document_upload_token')]);
    }

    public function index(Request $request)
    {
        $query = Application::with('program');
        
        $validated = $request->validate([
            'status' => ['nullable', 'string', Rule::in([
                'pending', 'submitted', 'under_review', 'pending_docs', 'for_interview',
                'approved', 'accepted', 'enrolled', 'rejected', 'cancelled',
            ])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        $applications = $query->latest()->paginate($validated['per_page'] ?? 15);

        return response()->json(['data' => $applications]);
    }

    public function show(string $id)
    {
        $application = Application::with('program')->findOrFail($id);
        return response()->json(['data' => $application]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                'pending', 'submitted', 'under_review', 'pending_docs', 'for_interview',
                'approved', 'accepted', 'enrolled', 'rejected', 'cancelled',
            ])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $application->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['notes'] ?? $application->admin_notes,
        ]);

        return response()->json(['data' => $application]);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'string', 'exists:applications,id'],
        ]);

        $ids = $validated['ids'];
        
        DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $application = Application::find($id);
                if (!$application) continue;

                // Restore slot if application was tied to a program
                if ($application->program_id) {
                    $program = Program::find($application->program_id);
                    if ($program) {
                        $program->increment('slots_left');
                        // Re-activate if it was full
                        if (!$program->is_active && $program->slots_left > 0) {
                            $program->update(['is_active' => $this->databaseBoolean(true)]);
                        }
                    }
                }
                $application->delete();
            }
        });

        Cache::forget('welcome_page_data');

        return response()->json(['message' => 'Applications deleted successfully']);
    }
    public function destroy(string $id)
    {
        $application = Application::findOrFail($id);
        
        // Restore slot if application was tied to a program
        if ($application->program_id) {
            $program = Program::find($application->program_id);
            if ($program) {
                $program->increment('slots_left');
                // Re-activate if it was full
                if (!$program->is_active && $program->slots_left > 0) {
                    $program->update(['is_active' => $this->databaseBoolean(true)]);
                }
            }
        }

        $application->delete();
        Cache::forget('welcome_page_data');

        return response()->json(['message' => 'Application deleted successfully']);
    }

    private function generateReferenceNumber(): string
    {
        do {
            $reference = 'BTECH-' . date('Y') . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Application::where('reference_number', $reference)->exists());

        return $reference;
    }

    private function validUploadToken(Application $application, string $token): bool
    {
        $storedHash = (string) $application->document_upload_token;

        return $storedHash !== ''
            && $token !== ''
            && hash_equals($storedHash, hash('sha256', $token));
    }

    private function databaseBoolean(bool $value): bool|\Illuminate\Contracts\Database\Query\Expression
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return DB::raw($value ? 'true' : 'false');
        }

        return $value;
    }
}
