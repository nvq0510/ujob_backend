<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'workplace_id',
        'room_number',
        'room_type',
        'status',
        'cleaning_status',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    public function workplace()
    {
        return $this->belongsTo(Workplace::class);
    }
}
