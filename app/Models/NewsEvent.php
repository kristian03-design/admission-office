<?php

namespace App\Models;

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
        'image_urls' => 'array',
        'is_active' => 'boolean',
    ];
}
