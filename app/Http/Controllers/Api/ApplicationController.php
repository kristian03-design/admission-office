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
                $program->slots_left = $newSlotsLeft;
                $program->is_active = $newSlotsLeft > 0;
                $program->save();
            }

            return Application::create($data);
        });

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
        $path = $request->file('file')->store('applications/'.$id, 'public');

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
            $application->update([$columnMap[$type] => $path]);
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
}
