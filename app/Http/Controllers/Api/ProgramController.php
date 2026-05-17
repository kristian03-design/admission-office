<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Program;
use App\Support\PublicCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Cache::remember(PublicCache::PROGRAMS_API, PublicCache::ttl(), function () {
            return Program::withCount('applications')->orderBy('name')->get();
        });

        return response()->json(['data' => $programs]);
    }

    public function updateSchedule(Request $request, string $id)
    {
        $program = Program::findOrFail($id);
        $program->update($request->only(['interview_schedule', 'interview_status']));
        PublicCache::clear();
        return response()->json(['message' => 'Course schedule updated successfully', 'data' => $program]);
    }

    public function updateSlotsLeft(Request $request, string $id)
    {
        $validated = $request->validate([
            'slots_left' => ['required', 'integer', 'min:0', 'max:3000'],
        ]);

        $program = Program::findOrFail($id);
        $slotsLeft = (int) $validated['slots_left'];
        $updateData = ['slots_left' => $slotsLeft];
        // Auto-deactivate if slots reach 0
        if ($slotsLeft <= 0) {
            $updateData['is_active'] = $this->databaseBoolean(false);
        }

        $program->update($updateData);
        $program->refresh();
        PublicCache::clear();

        return response()->json([
            'message' => 'Program slots updated and status synced',
            'data' => $program,
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $program = Program::findOrFail($id);
        $isActive = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        
        // If trying to activate but slots are 0, prevent it or handle gracefully
        if ($isActive && (int)($program->slots_left ?? 0) <= 0) {
            return response()->json([
                'message' => 'Cannot activate a program with 0 slots. Please add slots first.',
            ], 422);
        }

        $program->update([
            'is_active' => $this->databaseBoolean($isActive),
        ]);
        $program->refresh();
        PublicCache::clear();

        return response()->json([
            'message' => 'Program status updated successfully',
            'data' => $program,
        ]);
    }

    private function databaseBoolean(bool $value): bool|\Illuminate\Contracts\Database\Query\Expression
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            return DB::raw($value ? 'true' : 'false');
        }

        return $value;
    }
}
