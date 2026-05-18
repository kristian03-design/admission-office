<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsEvent;
use App\Support\PublicCache;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class NewsEventController extends Controller
{
    public function publicIndex()
    {
        $items = Cache::remember(PublicCache::NEWS_EVENTS_API, PublicCache::ttl(), function () {
            return NewsEvent::whereRaw('is_active = true')
                ->orderBy('sort_order')
                ->orderBy('event_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return response()->json(['data' => $items]);
    }

    public function index()
    {
        $items = NewsEvent::orderBy('sort_order')
            ->orderBy('event_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'required|in:news,event',
            'image_url' => 'nullable|string|max:1000',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|string|max:1000',
            'image_urls_json' => 'nullable|string',
            'image_items_json' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'clear_images' => 'nullable|boolean',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('images')) {
            $uploadedUrls = $this->storeUploadedImages($request->file('images'));
            $existingUrls = $this->decodeUrlList($validated['image_urls_json'] ?? null);
            $uploadedUrls = $this->orderImageUrls($validated['image_items_json'] ?? null, $existingUrls, $uploadedUrls);
            $validated['image_urls'] = $uploadedUrls;
            $validated['image_url'] = $uploadedUrls[0] ?? null;
        } elseif (!empty($validated['image_urls_json'])) {
            $decoded = json_decode($validated['image_urls_json'], true);
            if (is_array($decoded)) {
                $validated['image_urls'] = $decoded;
                $validated['image_url'] = $decoded[0] ?? null;
            }
        } elseif (!empty($validated['image_urls']) && is_array($validated['image_urls'])) {
            $validated['image_url'] = $validated['image_urls'][0] ?? null;
        }
        unset($validated['images'], $validated['images.*'], $validated['image_urls.*'], $validated['image_urls_json'], $validated['image_items_json'], $validated['clear_images']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'TRUE' : 'FALSE');
        }
        $item = NewsEvent::create($validated);
        $this->clearCache();

        return response()->json([
            'message' => 'News/Event created successfully.',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $item = NewsEvent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'sometimes|required|in:news,event',
            'image_url' => 'nullable|string|max:1000',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|string|max:1000',
            'image_urls_json' => 'nullable|string',
            'image_items_json' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'clear_images' => 'nullable|boolean',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('images')) {
            $currentUrls = is_array($item->image_urls) ? $item->image_urls : [];
            if (!$currentUrls && $item->image_url) {
                $currentUrls = [$item->image_url];
            }

            $retainedUrls = $this->decodeUrlList($validated['image_urls_json'] ?? null);
            $uploadedUrls = $this->storeUploadedImages($request->file('images'));
            $finalUrls = $this->orderImageUrls($validated['image_items_json'] ?? null, $retainedUrls, $uploadedUrls);

            $this->deleteStoredImages(array_diff($currentUrls, $finalUrls));
            $validated['image_urls'] = $finalUrls;
            $validated['image_url'] = $finalUrls[0] ?? null;
        } elseif (!empty($validated['image_urls_json'])) {
            $decoded = json_decode($validated['image_urls_json'], true);
            if (is_array($decoded)) {
                $validated['image_urls'] = $decoded;
                $validated['image_url'] = $decoded[0] ?? null;
            }
        } elseif (array_key_exists('image_urls', $validated) && is_array($validated['image_urls'])) {
            $validated['image_url'] = $validated['image_urls'][0] ?? null;
        } elseif (!empty($validated['clear_images'])) {
            $existingUrls = is_array($item->image_urls) ? $item->image_urls : [];
            if (!$existingUrls && $item->image_url) {
                $existingUrls = [$item->image_url];
            }
            $this->deleteStoredImages($existingUrls);
            $validated['image_urls'] = [];
            $validated['image_url'] = null;
        }
        unset($validated['images'], $validated['images.*'], $validated['image_urls.*'], $validated['image_urls_json'], $validated['image_items_json'], $validated['clear_images']);

        if (isset($validated['is_active'])) {
            $validated['is_active'] = \Illuminate\Support\Facades\DB::raw($validated['is_active'] ? 'true' : 'false');
        }
        $item->update($validated);
        $this->clearCache();

        return response()->json([
            'message' => 'News/Event updated successfully.',
            'data' => $item,
        ]);
    }

    public function destroy(string $id)
    {
        $item = NewsEvent::findOrFail($id);

        $urls = is_array($item->image_urls) ? $item->image_urls : [];
        if (!$urls && $item->image_url) {
            $urls = [$item->image_url];
        }
        $this->deleteStoredImages($urls);

        $item->delete();
        $this->clearCache();

        return response()->json(['message' => 'News/Event deleted successfully.']);
    }

    private function clearCache(): void
    {
        PublicCache::clear();
    }

    /**
     * @param  UploadedFile|array<int, UploadedFile>|null  $files
     * @return array<int, string>
     */
    private function storeUploadedImages(UploadedFile|array|null $files): array
    {
        if ($files === null) {
            return [];
        }

        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        $uploadedUrls = [];
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('news-events', 'supabase');
            abort_unless(is_string($path), 500, 'Unable to store uploaded image.');
            $uploadedUrls[] = $this->supabaseUrl($path);
        }

        return $uploadedUrls;
    }

    private function supabaseUrl(string $path): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('supabase');

        return $disk->url($path);
    }

    private function decodeUrlList(?string $json): array
    {
        if (!$json) {
            return [];
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter($decoded, fn ($url) => is_string($url) && $url !== ''));
    }

    private function orderImageUrls(?string $itemsJson, array $existingUrls, array $uploadedUrls): array
    {
        $items = $itemsJson ? json_decode($itemsJson, true) : null;
        if (!is_array($items)) {
            return array_values(array_merge($existingUrls, $uploadedUrls));
        }

        $ordered = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (($item['type'] ?? null) === 'existing') {
                $url = $item['url'] ?? null;
                if (is_string($url) && in_array($url, $existingUrls, true)) {
                    $ordered[] = $url;
                }
                continue;
            }

            if (($item['type'] ?? null) === 'file') {
                $index = $item['index'] ?? null;
                if (is_int($index) && array_key_exists($index, $uploadedUrls)) {
                    $ordered[] = $uploadedUrls[$index];
                }
            }
        }

        return array_values(array_unique(array_merge($ordered, $existingUrls, $uploadedUrls)));
    }

    private function deleteStoredImages(array $urls): void
    {
        foreach ($urls as $url) {
            if (!$url) continue;

            // Handle Supabase full URLs
            if (str_starts_with($url, 'https://')) {
                $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
                $prefix = '/storage/v1/object/public/' . $bucket . '/';
                if (str_contains($url, $prefix)) {
                    $key = substr($url, strpos($url, $prefix) + strlen($prefix));
                    Storage::disk('supabase')->delete($key);
                }
                continue;
            }

            // Handle legacy local paths
            $cleanPath = str_replace('/storage/', '', $url);
            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            }
        }
    }
}
