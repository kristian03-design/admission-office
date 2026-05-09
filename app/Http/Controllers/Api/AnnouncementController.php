<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements for admin.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $announcements]);
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'is_popup' => 'boolean',
            'popup_image' => 'nullable|string',
            'popup_image_file' => 'nullable|image|max:2048',
            'popup_button_text' => 'nullable|string',
            'popup_button_link' => 'nullable|string',
            'popup_always_show' => 'boolean',
            'clear_popup_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('popup_image_file')) {
            $path = $request->file('popup_image_file')->store('announcements', 'supabase');
            $validated['popup_image'] = Storage::disk('supabase')->url($path);
        }
        unset($validated['popup_image_file'], $validated['clear_popup_image']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
        $announcement = Announcement::create($validated);
        
        $this->clearCache();

        return response()->json([
            'message' => 'Announcement created successfully.',
            'data' => $announcement
        ], 201);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, string $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'sometimes|required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'is_popup' => 'boolean',
            'popup_image' => 'nullable|string',
            'popup_image_file' => 'nullable|image|max:2048',
            'popup_button_text' => 'nullable|string',
            'popup_button_link' => 'nullable|string',
            'popup_always_show' => 'boolean',
            'clear_popup_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('popup_image_file')) {
            $this->deleteStoredImage($announcement->popup_image);
            $path = $request->file('popup_image_file')->store('announcements', 'supabase');
            $validated['popup_image'] = Storage::disk('supabase')->url($path);
        } elseif (!empty($validated['clear_popup_image'])) {
            $this->deleteStoredImage($announcement->popup_image);
            $validated['popup_image'] = null;
        }
        unset($validated['popup_image_file'], $validated['clear_popup_image']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
        $announcement->update($validated);
        
        $this->clearCache();

        return response()->json([
            'message' => 'Announcement updated successfully.',
            'data' => $announcement
        ]);
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $this->deleteStoredImage($announcement->popup_image);
        $announcement->delete();
        
        $this->clearCache();

        return response()->json([
            'message' => 'Announcement deleted successfully.'
        ]);
    }

    /**
     * Clear the welcome page cache.
     */
    private function clearCache()
    {
        Cache::forget('welcome_page_data');
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Handle Supabase full URLs
        if (str_starts_with($path, 'https://')) {
            $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
            $prefixes = [
                '/storage/v1/object/public/' . $bucket . '/',
                '/storage/v1/s3/' . $bucket . '/',
            ];

            foreach ($prefixes as $prefix) {
                if (str_contains($path, $prefix)) {
                    $key = substr($path, strpos($path, $prefix) + strlen($prefix));
                    Storage::disk('supabase')->delete($key);
                    break;
                }
            }
            return;
        }

        // Handle legacy local paths
        $cleanPath = str_replace('/storage/', '', $path);
        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }
}
