<?php

namespace App\Models;

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
}
