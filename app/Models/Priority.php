<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'level',
    ];

    // You can add relationships here if needed, e.g. tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
