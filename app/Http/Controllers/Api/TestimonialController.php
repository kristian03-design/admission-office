<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('order')->get();
        return response()->json(['data' => $testimonials]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name'   => 'required|string|max:255',
            'author_role'   => 'required|string|max:255',
            'author_avatar' => 'nullable|string|max:65535',
            'author_avatar_file' => 'nullable|image|max:2048',
            'message'       => 'required|string',
            'order'         => 'nullable|integer',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('author_avatar_file')) {
            $validated['author_avatar'] = Storage::url($request->file('author_avatar_file')->store('testimonials', 'public'));
        }

        unset($validated['author_avatar_file']);

        $testimonial = Testimonial::create($validated);
        Cache::forget('welcome_page_data');

        return response()->json(['message' => 'Testimonial added.', 'data' => $testimonial]);
    }

    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        
        $validated = $request->validate([
            'author_name'   => 'sometimes|required|string|max:255',
            'author_role'   => 'sometimes|required|string|max:255',
            'author_avatar' => 'nullable|string|max:65535',
            'author_avatar_file' => 'nullable|image|max:2048',
            'clear_avatar'  => 'nullable|boolean',
            'message'       => 'sometimes|required|string',
            'order'         => 'nullable|integer',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('author_avatar_file')) {
            $this->deleteStoredAvatar($testimonial->author_avatar);
            $validated['author_avatar'] = Storage::url($request->file('author_avatar_file')->store('testimonials', 'public'));
        } elseif (!empty($validated['clear_avatar'])) {
            $this->deleteStoredAvatar($testimonial->author_avatar);
            $validated['author_avatar'] = null;
        }

        unset($validated['author_avatar_file'], $validated['clear_avatar']);

        $testimonial->update($validated);
        Cache::forget('welcome_page_data');

        return response()->json(['message' => 'Testimonial updated.', 'data' => $testimonial]);
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $this->deleteStoredAvatar($testimonial->author_avatar);
        $testimonial->delete();
        Cache::forget('welcome_page_data');
        return response()->json(['message' => 'Testimonial deleted.']);
    }

    private function deleteStoredAvatar(?string $url): void
    {
        if ($url && str_starts_with($url, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
