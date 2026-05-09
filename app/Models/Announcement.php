<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'is_active',
        'sort_order',
        'starts_at',
        'ends_at',
        'is_popup',
        'popup_image',
        'popup_button_text',
        'popup_button_link',
        'popup_always_show',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_popup' => 'boolean',
        'popup_always_show' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected function popupImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicImageUrl($value),
        );
    }

    private function publicImageUrl($url): ?string
    {
        if (!is_string($url) || $url === '') {
            return null;
        }

        $bucket = env('SUPABASE_S3_BUCKET', 'file_image');
        $publicBase = rtrim(env('SUPABASE_S3_URL', ''), '/');
        $cleanUrl = ltrim(str_replace('/storage/', '', $url), '/');

        if ($publicBase !== '') {
            $s3Prefix = '/storage/v1/s3/' . $bucket . '/';
            if (str_contains($url, $s3Prefix)) {
                $key = substr($url, strpos($url, $s3Prefix) + strlen($s3Prefix));

                return $publicBase . '/' . ltrim($key, '/');
            }

            if (preg_match('#^(applications|announcements|faculty-staff|news-events|testimonials)/#', $cleanUrl)) {
                return $publicBase . '/' . $cleanUrl;
            }
        }

        return $url;
    }
}
