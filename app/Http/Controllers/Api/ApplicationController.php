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


class ApplicationController extends Controller
{
    public function submitPublic(Request $request)
    {
        // Simple validation or accept all for now
        $data = $request->all();
        if (empty($data['reference_number'])) {
            $data['reference_number'] = 'BTECH-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        }
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
                    'is_active' => \Illuminate\Support\Facades\DB::raw($newSlotsLeft > 0 ? 'TRUE' : 'FALSE')
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

        // Send email to applicant
        if ($application->email && SystemSetting::get('email_notifications', '1') !== '0') {
            try {
                Mail::to($application->email)->send(new ApplicationSubmitted($application));
            } catch (\Exception $e) {
                // Log error but don't fail the request
                Log::error('Failed to send application submission email: ' . $e->getMessage());
            }
        }

        return response()->json(['data' => $application], 201);
    }

    public function uploadDocument(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        
        $request->validate([
            'document_type' => 'required|string',
            'file' => 'required|file',
        ]);

        $type = $request->document_type;
        $path = $request->file('file')->store('applications/'.$id, 'supabase');
        
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

        return response()->json(['data' => $application]);
    }

    public function index(Request $request)
    {
        $query = Application::with('program');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $applications = $query->latest()->paginate($request->input('per_page', 15));

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
        
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->notes ?? $application->admin_notes,
        ]);

        return response()->json(['data' => $application]);
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
                    $program->update(['is_active' => \Illuminate\Support\Facades\DB::raw('TRUE')]);
                }
            }
        }

        $application->delete();
        Cache::forget('welcome_page_data');

        return response()->json(['message' => 'Application deleted successfully']);
    }
}
