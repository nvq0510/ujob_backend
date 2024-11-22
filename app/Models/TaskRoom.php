<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'room_id',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

