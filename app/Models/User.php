<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Task;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * All tasks assigned to this user.
     */
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }
    
}
