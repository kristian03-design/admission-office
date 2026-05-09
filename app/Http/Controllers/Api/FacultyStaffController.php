<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacultyStaffController extends Controller
{
    private string $path = 'faculty-staff.json';

    public function index()
    {
        return response()->json(['data' => $this->readItems()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateItem($request);
        $items = $this->readItems();

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('faculty-staff', 'public');
        }

        $validated['id'] = $this->uniqueId($validated['name'], $items);
        $validated['order'] = $validated['order'] ?? (count($items) + 1);
        $validated['is_active'] = $request->boolean('is_active', true);
        unset($validated['image_file'], $validated['clear_image']);

        $items[] = $validated;
        $this->writeItems($items);

        return response()->json(['message' => 'Faculty/staff member added.', 'data' => $validated], 201);
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateItem($request);
        $items = $this->readItems();
        $updated = null;

        foreach ($items as &$item) {
            if (($item['id'] ?? '') !== $id) {
                continue;
            }

            $item = array_merge($item, $validated, [
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($request->hasFile('image_file')) {
                $this->deleteStoredImage($item['image'] ?? null);
                $item['image'] = $request->file('image_file')->store('faculty-staff', 'public');
            } elseif ($request->boolean('clear_image')) {
                $this->deleteStoredImage($item['image'] ?? null);
                $item['image'] = '';
            }

            unset($item['image_file'], $item['clear_image']);
            $updated = $item;
            break;
        }

        if (!$updated) {
            abort(404, 'Faculty/staff member not found.');
        }

        $this->writeItems($items);

        return response()->json(['message' => 'Faculty/staff member updated.', 'data' => $updated]);
    }

    public function destroy(string $id)
    {
        $items = $this->readItems();
        $remaining = array_values(array_filter($items, fn ($item) => ($item['id'] ?? '') !== $id));

        if (count($remaining) === count($items)) {
            abort(404, 'Faculty/staff member not found.');
        }

        $deleted = collect($items)->firstWhere('id', $id);
        $this->deleteStoredImage($deleted['image'] ?? null);
        $this->writeItems($remaining);

        return response()->json(['message' => 'Faculty/staff member deleted.']);
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'note' => ['required', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:80'],
            'image_file' => ['nullable', 'image', 'max:2048'],
            'clear_image' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }

    private function readItems(): array
    {
        $json = \App\Models\SystemSetting::get('faculty_staff_data', '[]');
        $decoded = json_decode($json, true);

        if (!is_array($decoded)) {
            return [];
        }

        usort($decoded, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return array_values($decoded);
    }

    private function writeItems(array $items): void
    {
        usort($items, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        \App\Models\SystemSetting::set('faculty_staff_data', json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function uniqueId(string $name, array $items): string
    {
        $base = Str::slug($name) ?: 'member';
        $ids = array_column($items, 'id');
        $id = $base;
        $i = 2;

        while (in_array($id, $ids, true)) {
            $id = "{$base}-{$i}";
            $i++;
        }

        return $id;
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Handle both full URLs/prefixed paths and relative paths
        $cleanPath = str_replace('/storage/', '', $path);
        
        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }
}
