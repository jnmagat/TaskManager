<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Comment;

class Task extends Model
{
     protected $fillable = [
        'title',
        'description',
        'category_id',
        'priority_id',
        'status_id',
        'due_date',
    ];
    
    protected $casts = [
        'due_date' => 'date', 
    ];
    
    // Relationships

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The users assigned to this task.
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Scope to retrieve tasks whose status name is "Done".
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereHas('status', function (Builder $q) {
            $q->where('name', 'Done');
        });
    }
}
