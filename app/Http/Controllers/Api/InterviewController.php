<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Program;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $programId = $request->program_id;
        $search = $request->search;

        $query = Interview::where('program_id', $programId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', "%$search%")
                  ->orWhere('reference_number', 'like', "%$search%");
            });
        }

        return response()->json(['data' => $query->get()]);
    }

    public function sync(Request $request, $programId)
    {
        $schedules = $request->schedules; // Array of objects

        // Get all application_ids and student_names in the incoming payload
        $incomingIds = array_filter(array_column($schedules, 'application_id'));
        $incomingNames = array_filter(array_column($schedules, 'student_name'));

        // Delete interviews that were removed from the UI for this program
        // We only keep those that match either application_id or student_name in the incoming payload
        Interview::where('program_id', $programId)
            ->where(function($query) use ($incomingIds, $incomingNames) {
                if (count($incomingIds) > 0) {
                    $query->whereNotIn('application_id', $incomingIds);
                }
            })
            ->where(function($query) use ($incomingIds, $incomingNames) {
                if (count($incomingNames) > 0) {
                    $query->whereNotIn('student_name', $incomingNames);
                }
            })->delete();

        foreach ($schedules as $item) {
            $interview = Interview::where('program_id', $programId)
                ->where(function($q) use ($item) {
                    if (!empty($item['application_id'])) {
                        $q->where('application_id', $item['application_id']);
                    } else {
                        $q->where('student_name', $item['student_name']);
                    }
                })->first();

            $isNew = false;
            $scheduleChanged = false;
            $statusChanged = false;

            if (!$interview) {
                $isNew = true;
                $interview = new Interview();
                $interview->program_id = $programId;
                $interview->application_id = $item['application_id'] ?? null;
                $interview->student_name = $item['student_name'];
                $interview->reference_number = $item['reference_number'] ?? null;
            } else {
                if ($interview->interview_date != $item['interview_date'] || $interview->interview_time != $item['interview_time']) {
                    $scheduleChanged = true;
                }
                $incomingStatus = $item['status'] ?? 'Pending';
                if ((string) $interview->status !== (string) $incomingStatus) {
                    $statusChanged = true;
                }
            }

            $interview->interview_date = $item['interview_date'];
            $interview->interview_time = $item['interview_time'];
            $interview->status = $item['status'] ?? 'Pending';
            $interview->save();

            // Keep application status in sync so dashboard KPIs update.
            if ($interview->application_id) {
                $application = \App\Models\Application::find($interview->application_id);
                if ($application) {
                    $s = strtolower(trim((string) $interview->status));
                    // If interview is done/no-show/cancelled, it should no longer count as "for_interview"
                    if (in_array($s, ['completed', 'no show', 'no_show', 'noshow', 'cancelled', 'canceled'], true)) {
                        if ($application->status === 'for_interview') {
                            $application->update(['status' => 'under_review']);
                        }
                    }
                    // If interview is pending/scheduled and has date, keep as for_interview
                    if (!empty($interview->interview_date) && in_array($s, ['pending', 'scheduled', 'interview scheduled'], true)) {
                        if ($application->status !== 'for_interview') {
                            $application->update(['status' => 'for_interview']);
                        }
                    }
                }
            }

            // Send notification if it's new or the schedule changed, and they have an interview date set
            if (($isNew || $scheduleChanged) && !empty($interview->interview_date)) {
                if ($interview->application_id) {
                    $application = \App\Models\Application::find($interview->application_id);
                    if ($application && !empty($application->email) && \App\Models\SystemSetting::get('email_notifications', '1') !== '0') {
                        try {
                            \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\InterviewScheduled($interview, $application));
                            
                            // Also update application status if not already
                            if ($application->status !== 'for_interview') {
                                $application->update(['status' => 'for_interview']);
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send interview schedule email: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Schedules synced successfully']);
    }
}
