<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get all settings as a key-value associative array.
     */
    public static function all_as_array(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Get a single setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $record = static::where('key', $key)->first();
        return $record ? $record->value : $default;
    }

    /**
     * Set a single setting value by key (upsert).
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
