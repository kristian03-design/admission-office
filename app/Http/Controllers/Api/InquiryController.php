<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Mail\NewInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * POST /api/contact
     * Publicly accessible endpoint for contact form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
        ]);

        $inquiry = null;

        try {
            $inquiry = ContactInquiry::create($validated + ['status' => 'pending']);
        } catch (\Exception $e) {
            Log::error('Contact Inquiry Save Error: ' . $e->getMessage());

            $inquiry = (object) $validated;
            $inquiry->created_at = now();
        }

        try {
            $recipients = array_values(array_unique(array_filter([
                SystemSetting::where('key', 'admissions_email')->first()?->value,
                config('mail.from.address'),
            ], fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))));

            if ($recipients === []) {
                Log::warning('Contact inquiry email skipped: no valid recipient configured.');
            } else {
                Mail::to($recipients)->send(new NewInquiry($inquiry));
            }
        } catch (\Exception $e) {
            Log::error('Mail Error: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Thank you for your message. We will get back to you soon.'
        ], 201);
    }

    /**
     * GET /api/admin/inquiries
     * Admin only list.
     */
    public function index()
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $inquiries]);
    }

    /**
     * DELETE /api/admin/inquiries/{id}
     */
    public function destroy(string $id)
    {
        ContactInquiry::findOrFail($id)->delete();
        return response()->json(['message' => 'Inquiry deleted.']);
    }
}
