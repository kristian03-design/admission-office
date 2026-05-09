<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'document_upload_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    protected function photoPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    protected function birthCertificatePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    protected function reportCardPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    protected function goodMoralPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    protected function torPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    protected function diplomaPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->publicStorageUrl($value),
        );
    }

    private function publicStorageUrl($url): ?string
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
