<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class NewsEvent extends Model
{
    protected $fillable = [
        'title',
        'summary',
        'content',
        'type',
        'image_url',
        'image_urls',
        'event_date',
        'location',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicImageUrl($value),
        );
    }

    protected function imageUrls(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $urls = is_string($value) ? json_decode($value, true) : $value;

                if (!is_array($urls)) {
                    return [];
                }

                return array_values(array_filter(array_map(
                    fn ($url) => $this->publicImageUrl($url),
                    $urls
                )));
            },
            set: fn ($value) => is_array($value) ? json_encode(array_values($value)) : $value,
        );
    }

    private function publicImageUrl($url): ?string
    {
        if (!is_string($url) || $url === '') {
            return null;
        }

        $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
        $publicBase = rtrim(env('SUPABASE_S3_URL', ''), '/');

        if ($publicBase !== '') {
            $s3Prefix = '/storage/v1/s3/' . $bucket . '/';
            if (str_contains($url, $s3Prefix)) {
                $key = substr($url, strpos($url, $s3Prefix) + strlen($s3Prefix));

                return $publicBase . '/' . ltrim($key, '/');
            }
        }

        return $url;
    }
}
