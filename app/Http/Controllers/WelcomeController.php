<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\Announcement;
use App\Models\Program;
use App\Models\NewsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        // Cache data for 5 minutes to ensure high performance
        $data = Cache::remember('welcome_page_data', 300, function () {
            $announcements = Announcement::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'settings' => SystemSetting::all_as_array(),
                'announcements' => $announcements,
                'tickerAnnouncements' => $announcements->where('is_popup', false)->values(),
                'popupAnn' => $announcements->firstWhere('is_popup', true),
                'programs' => Program::orderBy('name')->get(),
                'testimonials' => \App\Models\Testimonial::where('is_active', true)
                    ->orderBy('order')
                    ->get()
            ];
        });

        return view('welcome', $data);
    }

    /**
     * Display details for a specific program.
     */
    public function showProgram(string $id)
    {
        $program = Program::findOrFail($id);
        $settings = SystemSetting::all_as_array();
        
        return view('course-details', [
            'program' => $program,
            'settings' => $settings,
            'careerOpportunities' => $this->careerOpportunitiesFor($program),
        ]);
    }

    /**
     * Display the About page.
     */
    public function about()
    {
        return view('about', [
            'settings' => SystemSetting::all_as_array(),
            'team' => $this->facultyStaff(),
        ]);
    }

    /**
     * Display the public News & Events page.
     */
    public function newsEvents()
    {
        return view('news-events', [
            'settings' => SystemSetting::all_as_array(),
            'newsEvents' => NewsEvent::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('event_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(6)
                ->withQueryString(),
        ]);
    }

    /**
     * Display a single News/Event details page.
     */
    public function showNewsEvent(string $id)
    {
        $settings = SystemSetting::all_as_array();
        $item = NewsEvent::where('is_active', true)->findOrFail($id);

        $gallery = (is_array($item->image_urls) && count($item->image_urls))
            ? $item->image_urls
            : ($item->image_url ? [$item->image_url] : []);

        return view('news-event-details', [
            'settings' => $settings,
            'item' => $item,
            'gallery' => $gallery,
        ]);
    }

    private function facultyStaff(): array
    {
        if (!Storage::disk('local')->exists('faculty-staff.json')) {
            return [];
        }

        $items = json_decode(Storage::disk('local')->get('faculty-staff.json'), true);
        if (!is_array($items)) {
            return [];
        }

        $items = array_values(array_filter($items, fn ($item) => ($item['is_active'] ?? true)));
        usort($items, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return $items;
    }

    private function careerOpportunitiesFor(Program $program): array
    {
        $careers = $program->career_opportunities;
        if (is_string($careers)) {
            $decoded = json_decode($careers, true);
            $careers = is_array($decoded) ? $decoded : [];
        }

        if (is_array($careers) && count($careers) > 0) {
            return array_values(array_filter(array_map(function ($career) {
                if (is_array($career)) {
                    return $career['name'] ?? $career['title'] ?? null;
                }

                return $career;
            }, $careers)));
        }

        $name = strtolower($program->name ?? '');
        $department = strtolower($program->department ?? '');

        if (str_contains($name, 'information technology')) {
            return ['Software Developer', 'Web Developer', 'Network Administrator', 'Database Administrator', 'IT Support Specialist'];
        }

        if (str_contains($name, 'hospitality')) {
            return ['Hotel Operations Supervisor', 'Restaurant Manager', 'Front Office Associate', 'Events Coordinator', 'Food and Beverage Supervisor'];
        }

        if (str_contains($name, 'tourism')) {
            return ['Tourism Officer', 'Travel Consultant', 'Tour Coordinator', 'Flight Attendant', 'Destination Marketing Associate'];
        }

        if (str_contains($department, 'education') || str_contains($name, 'education')) {
            return ['Licensed Teacher', 'Curriculum Assistant', 'Learning Facilitator', 'Academic Coordinator', 'Education Program Staff'];
        }

        if (str_contains($department, 'accountancy') || str_contains($name, 'accounting') || str_contains($name, 'accountancy') || str_contains($name, 'auditing')) {
            return ['Accounting Associate', 'Auditor', 'Tax Assistant', 'Bookkeeper', 'Financial Reporting Staff'];
        }

        if (str_contains($department, 'business') || str_contains($name, 'business') || str_contains($name, 'entrepreneurship') || str_contains($name, 'economics')) {
            return ['Business Analyst', 'Marketing Associate', 'Operations Supervisor', 'Financial Services Associate', 'Entrepreneur'];
        }

        return ['Industry Specialist', 'Program Coordinator', 'Research Assistant', 'Administrative Officer', 'Professional Consultant'];
    }
}
