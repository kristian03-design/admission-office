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
            $path = $request->file('author_avatar_file')->store('testimonials', 'supabase');
            $validated['author_avatar'] = Storage::disk('supabase')->url($path);
        }

        unset($validated['author_avatar_file']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
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
            $path = $request->file('author_avatar_file')->store('testimonials', 'supabase');
            $validated['author_avatar'] = Storage::disk('supabase')->url($path);
        } elseif (!empty($validated['clear_avatar'])) {
            $this->deleteStoredAvatar($testimonial->author_avatar);
            $validated['author_avatar'] = null;
        }

        unset($validated['author_avatar_file'], $validated['clear_avatar']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
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

    private function deleteStoredAvatar(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Handle Supabase full URLs
        if (str_starts_with($path, 'https://')) {
            $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
            $prefix = '/storage/v1/object/public/' . $bucket . '/';
            if (str_contains($path, $prefix)) {
                $key = substr($path, strpos($path, $prefix) + strlen($prefix));
                Storage::disk('supabase')->delete($key);
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
