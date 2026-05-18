<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\PublicCache;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
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

        $avatarFile = $request->file('author_avatar_file');
        if ($avatarFile instanceof UploadedFile) {
            $validated['author_avatar'] = $this->storeSupabaseAvatar($avatarFile);
        }

        unset($validated['author_avatar_file']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
        $testimonial = Testimonial::create($validated);
        PublicCache::clear();

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

        $avatarFile = $request->file('author_avatar_file');
        if ($avatarFile instanceof UploadedFile) {
            $this->deleteStoredAvatar($testimonial->author_avatar);
            $validated['author_avatar'] = $this->storeSupabaseAvatar($avatarFile);
        } elseif (!empty($validated['clear_avatar'])) {
            $this->deleteStoredAvatar($testimonial->author_avatar);
            $validated['author_avatar'] = null;
        }

        unset($validated['author_avatar_file'], $validated['clear_avatar']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
        $testimonial->update($validated);
        PublicCache::clear();

        return response()->json(['message' => 'Testimonial updated.', 'data' => $testimonial]);
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $this->deleteStoredAvatar($testimonial->author_avatar);
        $testimonial->delete();
        PublicCache::clear();
        return response()->json(['message' => 'Testimonial deleted.']);
    }

    private function storeSupabaseAvatar(UploadedFile $file): string
    {
        $path = $file->store('testimonials', 'supabase');
        abort_unless(is_string($path), 500, 'Unable to store uploaded avatar.');

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('supabase');

        return $disk->url($path);
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
