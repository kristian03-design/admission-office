<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
