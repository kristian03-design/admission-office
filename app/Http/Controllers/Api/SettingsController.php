<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * GET /api/admin/settings
     * Returns all system settings as a flat key-value object.
     */
    public function show()
    {
        $settings = SystemSetting::all_as_array();

        // Also append total applications (read-only computed value)
        $settings['total_applications'] = Application::count();

        return response()->json(['data' => $settings]);
    }

    /**
     * GET /api/settings
     * Publicly accessible settings for the application form.
     */
    public function publicShow()
    {
        $settings = SystemSetting::whereIn('key', [
            'school_year',
            'application_deadline',
            'institution_name',
            'admissions_email',
            'campus_address',
            'contact_address',
            'accept_applications',
            'scholarship_applications',
        ])->pluck('value', 'key');

        return response()->json(['data' => $settings]);
    }

    /**
     * PUT /api/admin/settings
     * Saves provided key-value pairs.
     */
    public function update(Request $request)
    {
        $allowed = [
            'school_year',
            'application_deadline',
            'interview_schedule_text',
            'accept_applications',
            'email_notifications',
            'dashboard_notifications',
            'scholarship_applications',
            'institution_name',
            'admissions_email',
            'campus_address',
            'contact_address',
            'hero_headline',
            'hero_subheadline',
            'school_year_label',
            'cta_text',
            'cta_link',
            'contact_phone',
            'contact_office_hours',
            'facebook_link',
            'twitter_link',
            'instagram_link',
            'cta_section_headline',
            'cta_section_subheadline',
            'cta_section_button_text',
            'feature_1_title', 'feature_1_desc', 'feature_1_icon',
            'feature_2_title', 'feature_2_desc', 'feature_2_icon',
            'feature_3_title', 'feature_3_desc', 'feature_3_icon',
            'feature_4_title', 'feature_4_desc', 'feature_4_icon',
            'feature_5_title', 'feature_5_desc', 'feature_5_icon',
            'feature_6_title', 'feature_6_desc', 'feature_6_icon',
        ];

        $data = $request->only($allowed);

        if (array_key_exists('campus_address', $data) && ! array_key_exists('contact_address', $data)) {
            $data['contact_address'] = $data['campus_address'];
        }

        if (array_key_exists('contact_address', $data) && ! array_key_exists('campus_address', $data)) {
            $data['campus_address'] = $data['contact_address'];
        }

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value);
        }

        Cache::forget('welcome_page_data');

        // Re-read and return updated settings
        $settings = SystemSetting::all_as_array();
        $settings['total_applications'] = Application::count();

        return response()->json([
            'message' => 'Settings saved successfully.',
            'data'    => $settings,
        ]);
    }
}
