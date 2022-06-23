<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_label'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }
}
