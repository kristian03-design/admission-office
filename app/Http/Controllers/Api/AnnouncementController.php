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
            $validated['popup_image'] = Storage::url($request->file('popup_image_file')->store('announcements', 'public'));
        }
        unset($validated['popup_image_file'], $validated['clear_popup_image']);

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
    public function update(Request $request, $id)
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
            $validated['popup_image'] = Storage::url($request->file('popup_image_file')->store('announcements', 'public'));
        } elseif (!empty($validated['clear_popup_image'])) {
            $this->deleteStoredImage($announcement->popup_image);
            $validated['popup_image'] = null;
        }
        unset($validated['popup_image_file'], $validated['clear_popup_image']);

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
    public function destroy($id)
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

    private function deleteStoredImage(?string $url): void
    {
        if ($url && str_starts_with($url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
