<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

