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
            $announcements = Announcement::whereRaw('is_active = true')
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();

            $programs = Program::orderBy('name')->get();

            return [
                'settings' => SystemSetting::all_as_array(),
                'announcements' => $announcements,
                'tickerAnnouncements' => $announcements
                    ->where('is_popup', false)
                    ->unique(fn ($announcement) => trim(mb_strtolower($announcement->message ?? '')))
                    ->values(),
                'popupAnn' => $announcements->firstWhere('is_popup', true),
                'programs' => $programs->isNotEmpty() ? $programs : $this->fallbackPrograms(),
                'testimonials' => \App\Models\Testimonial::whereRaw('is_active = true')
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
        return view('about', array_merge([
            'settings' => SystemSetting::all_as_array(),
            'team' => $this->facultyStaff(),
        ], $this->getFooterData()));
    }

    /**
     * Display the public News & Events page.
     */
    public function newsEvents()
    {
        return view('news-events', array_merge([
            'settings' => SystemSetting::all_as_array(),
            'newsEvents' => NewsEvent::whereRaw('is_active = true')
                ->orderBy('sort_order')
                ->orderBy('event_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(6)
                ->withQueryString(),
        ], $this->getFooterData()));
    }

    /**
     * Display a single News/Event details page.
     */
    public function showNewsEvent(string $id)
    {
        $settings = SystemSetting::all_as_array();
        $item = NewsEvent::whereRaw('is_active = true')->findOrFail($id);

        $gallery = (is_array($item->image_urls) && count($item->image_urls))
            ? $item->image_urls
            : ($item->image_url ? [$item->image_url] : []);

        return view('news-event-details', array_merge([
            'settings' => $settings,
            'item' => $item,
            'gallery' => $gallery,
        ], $this->getFooterData()));
    }

    public function howToApply()
    {
        return view('how-to-apply', array_merge([
            'settings' => SystemSetting::all_as_array(),
        ], $this->getFooterData()));
    }

    public function requirements()
    {
        return view('requirements', array_merge([
            'settings' => SystemSetting::all_as_array(),
        ], $this->getFooterData()));
    }

    public function scholarshipPrograms()
    {
        return view('scholarship-programs', array_merge([
            'settings' => SystemSetting::all_as_array(),
        ], $this->getFooterData()));
    }

    public function tuitionFees()
    {
        return view('tuition-fees', array_merge([
            'settings' => SystemSetting::all_as_array(),
        ], $this->getFooterData()));
    }

    public function faqs()
    {
        return view('faqs', array_merge([
            'settings' => SystemSetting::all_as_array(),
        ], $this->getFooterData()));
    }

    private function getFooterData()
    {
        return [
            'footerPrograms' => Program::orderBy('name')->take(6)->get()
        ];
    }

    private function facultyStaff(): array
    {
        $json = \App\Models\SystemSetting::get('faculty_staff_data', '[]');
        $items = json_decode($json, true);

        if (!is_array($items)) {
            return [];
        }

        $items = array_values(array_filter($items, fn ($item) => ($item['is_active'] ?? true)));
        $items = array_map(function ($item) {
            if (!is_array($item)) {
                return $item;
            }

            $item['image'] = $this->publicStorageUrl($item['image'] ?? null) ?? '';

            return $item;
        }, $items);
        usort($items, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return $items;
    }

    private function fallbackPrograms()
    {
        return collect(config('academic_programs.fallback', []))
            ->map(fn ($program) => (object) array_merge([
                'id' => null,
                'duration_years' => 4,
                'schedule' => 'Day / Evening',
                'slots_left' => 1500,
                'is_active' => true,
            ], $program))
            ->sortBy('name')
            ->values();
    }

    private function publicStorageUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
        $publicBase = rtrim(env('SUPABASE_S3_URL', ''), '/');
        $cleanUrl = ltrim(str_replace('/storage/', '', $url), '/');

        if ($publicBase !== '') {
            $s3Prefix = '/storage/v1/s3/' . $bucket . '/';
            if (str_contains($url, $s3Prefix)) {
                $key = substr($url, strpos($url, $s3Prefix) + strlen($s3Prefix));

                return $publicBase . '/' . ltrim($key, '/');
            }

            if (preg_match('#^(applications|announcements|faculty-staff|news-events|testimonials)/#', $cleanUrl)) {
                return $publicBase . '/' . $cleanUrl;
            }
        }

        return $url;
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
