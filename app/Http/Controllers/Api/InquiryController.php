<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SystemSetting;
use App\Mail\InquiryReply;
use App\Mail\NewInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
            $payload = $validated;
            if (Schema::hasColumn('contact_inquiries', 'status')) {
                $payload['status'] = 'pending';
            }

            $inquiry = ContactInquiry::create($payload);
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
        try {
            if (! Schema::hasTable('contact_inquiries')) {
                return response()->json(['data' => []]);
            }

            $query = ContactInquiry::query();
            if (Schema::hasColumn('contact_inquiries', 'created_at')) {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }

            return response()->json(['data' => $query->get()]);
        } catch (\Exception $e) {
            Log::error('Contact Inquiry List Error: ' . $e->getMessage());

            return response()->json(['data' => []]);
        }
    }

    /**
     * PATCH /api/admin/inquiries/{id}/status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,read,replied'],
        ]);

        if (! Schema::hasColumn('contact_inquiries', 'status')) {
            return response()->json([
                'message' => 'Inquiry status column is not available yet.',
            ]);
        }

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Inquiry status updated.',
            'data' => $inquiry,
        ]);
    }

    /**
     * POST /api/admin/inquiries/{id}/reply
     */
    public function reply(Request $request, string $id)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $inquiry = ContactInquiry::findOrFail($id);

        if (! filter_var($inquiry->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'This inquiry does not have a valid sender email address.',
            ], 422);
        }

        try {
            Mail::to($inquiry->email)->send(new InquiryReply($inquiry, $validated['message']));

            $updates = [];
            if (Schema::hasColumn('contact_inquiries', 'status')) {
                $updates['status'] = 'replied';
            }
            if (Schema::hasColumn('contact_inquiries', 'reply_message')) {
                $updates['reply_message'] = $validated['message'];
            }
            if (Schema::hasColumn('contact_inquiries', 'replied_at')) {
                $updates['replied_at'] = now();
            }
            if ($updates !== []) {
                $inquiry->update($updates);
            }

            return response()->json([
                'message' => 'Reply sent successfully.',
                'data' => $inquiry->fresh(),
            ]);
        } catch (\Exception $e) {
            Log::error('Inquiry Reply Mail Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Reply could not be sent. Please check the mail settings.',
            ], 500);
        }
    }

    /**
     * DELETE /api/admin/inquiries/{id}
     */
    public function destroy(string $id)
    {
        if (! Schema::hasTable('contact_inquiries')) {
            return response()->json(['message' => 'Inquiry deleted.']);
        }

        ContactInquiry::findOrFail($id)->delete();
        return response()->json(['message' => 'Inquiry deleted.']);
    }
}
