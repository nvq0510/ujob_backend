<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workplace_id',
        'task_date',
        'priority',
        'status',
        'start_time',
        'end_time',
        'total_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }

    public function images()
    {
        return $this->hasMany(TaskImage::class);
    }

    public function rooms()
    {
        return $this->hasMany(TaskRoom::class);
    }
}
