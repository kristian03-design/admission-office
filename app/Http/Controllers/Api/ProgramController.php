<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Program;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query();

        // Admin/staff dashboard (authenticated) can see all programs.
        if (!$request->user('sanctum')) {
            // Public endpoints (e.g. apply form) only see selectable programs.
            $query->whereRaw('is_active = true')
                ->where('slots_left', '>', 0);
        }

        $programs = $query->get();
        return response()->json(['data' => $programs]);
    }

    public function updateSchedule(Request $request, string $id)
    {
        $program = Program::findOrFail($id);
        $program->update($request->only(['interview_schedule', 'interview_status']));
        return response()->json(['message' => 'Course schedule updated successfully', 'data' => $program]);
    }

    public function updateSlotsLeft(Request $request, string $id)
    {
        $validated = $request->validate([
            'slots_left' => ['required', 'integer', 'min:0', 'max:3000'],
        ]);

        $program = Program::findOrFail($id);
        $slotsLeft = (int) $validated['slots_left'];
        $program->slots_left = $slotsLeft;
        $program->is_active = $slotsLeft > 0;
        $program->save();

        return response()->json([
            'message' => 'Program slots left updated successfully',
            'data' => $program,
        ]);
    }
}
