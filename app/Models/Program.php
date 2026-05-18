<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'slots_left' => 'integer',
        'career_opportunities' => 'array',
        'core_areas' => 'array',
        'is_active' => 'boolean',
        'has_board_exam' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
