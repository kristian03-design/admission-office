<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Application;
use App\Models\Program;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── KPI stats ─────────────────────────────────────────────────
        $total    = Application::count();
        $pending  = Application::whereIn('status', ['pending', 'submitted', 'under_review', 'pending_docs'])->count();
        $approved = Application::whereIn('status', ['approved', 'accepted', 'enrolled'])->count();
        $rejected = Application::whereIn('status', ['rejected', 'cancelled'])->count();
        $interview = Application::where('status', 'for_interview')->count();

        // ── Recent applications (last 10) ───────────────────────────
        $recent = Application::with('program')->latest()->take(10)->get();

        // ── Monthly trend (submissions per month) ───────────────────
        $trend = Application::selectRaw("TO_CHAR(submitted_at, 'YYYY-MM') as month, COUNT(*) as count")
            ->whereNotNull('submitted_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => ['month' => $r->month, 'count' => $r->count]);

        // ── By applicant type breakdown ──────────────────────────────
        $byType = Application::selectRaw('applicant_type, COUNT(*) as count')
            ->whereNotNull('applicant_type')
            ->groupBy('applicant_type')
            ->pluck('count', 'applicant_type');

        // ── Per-program breakdown ────────────────────────────────────
        $byProgram = Application::selectRaw('first_choice, COUNT(*) as total,
                SUM(CASE WHEN status IN (\'approved\',\'accepted\',\'enrolled\') THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status IN (\'pending\',\'submitted\',\'under_review\',\'pending_docs\',\'for_interview\') THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status IN (\'rejected\',\'cancelled\') THEN 1 ELSE 0 END) as rejected,
                AVG(CASE
                    WHEN gwa_grade_11 IS NOT NULL AND gwa_grade_11 != \'\' AND gwa_grade_12 IS NOT NULL AND gwa_grade_12 != \'\' THEN (CAST(NULLIF(gwa_grade_11, \'\') AS NUMERIC) + CAST(NULLIF(gwa_grade_12, \'\') AS NUMERIC)) / 2.0
                    WHEN gwa_grade_11 IS NOT NULL AND gwa_grade_11 != \'\' THEN CAST(NULLIF(gwa_grade_11, \'\') AS NUMERIC)
                    WHEN gwa_grade_12 IS NOT NULL AND gwa_grade_12 != \'\' THEN CAST(NULLIF(gwa_grade_12, \'\') AS NUMERIC)
                    ELSE NULL END) as avg_gwa')
            ->whereNotNull('first_choice')
            ->groupBy('first_choice')
            ->orderByDesc('total')
            ->get();

        // ── Eligibility summary (PWD / solo / indigenous / 4Ps) ──────────────────
        $yesValues = ['Yes', 'yes'];
        $pwdCount = Application::whereIn('pwd', $yesValues)->count();
        $soloCount = Application::whereIn('solo_parent', $yesValues)->count();
        $indigenousCount = Application::whereIn('indigenous', $yesValues)->count();
        $foursCount = Application::whereIn('four_ps', $yesValues)->count();
        $noneCount = Application::where(function ($query) use ($yesValues) {
                $query->whereNull('pwd')->orWhereNotIn('pwd', $yesValues);
            })
            ->where(function ($query) use ($yesValues) {
                $query->whereNull('solo_parent')->orWhereNotIn('solo_parent', $yesValues);
            })
            ->where(function ($query) use ($yesValues) {
                $query->whereNull('indigenous')->orWhereNotIn('indigenous', $yesValues);
            })
            ->where(function ($query) use ($yesValues) {
                $query->whereNull('four_ps')->orWhereNotIn('four_ps', $yesValues);
            })
            ->count();

        // ── Overall average GWA ───────────────────────────────────────
        $avgGwa = Application::selectRaw('AVG(CASE
                WHEN gwa_grade_11 IS NOT NULL AND gwa_grade_11 != \'\' AND gwa_grade_12 IS NOT NULL AND gwa_grade_12 != \'\' THEN (CAST(NULLIF(gwa_grade_11, \'\') AS NUMERIC) + CAST(NULLIF(gwa_grade_12, \'\') AS NUMERIC)) / 2.0
                WHEN gwa_grade_11 IS NOT NULL AND gwa_grade_11 != \'\' THEN CAST(NULLIF(gwa_grade_11, \'\') AS NUMERIC)
                WHEN gwa_grade_12 IS NOT NULL AND gwa_grade_12 != \'\' THEN CAST(NULLIF(gwa_grade_12, \'\') AS NUMERIC)
                ELSE NULL END) as avg_gwa')
            ->value('avg_gwa');

        $stats = [
            'total_applications'   => $total,
            'pending_applications' => $pending,
            'approved_applications'=> $approved,
            'rejected_applications'=> $rejected,
            'interview_applications' => $interview,
            'avg_gwa'              => $avgGwa ? round($avgGwa, 2) : null,
            'pwd_count'            => $pwdCount,
            'solo_parent_count'    => $soloCount,
            'indigenous_count'     => $indigenousCount,
            'four_ps_count'        => $foursCount,
            'none_count'           => $noneCount,
            'recent_applications'  => $recent,
            'monthly_trend'        => $trend,
            'by_type'              => $byType,
            'by_program'           => $byProgram,
        ];

        return response()->json(['data' => $stats]);
    }
}
