<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'zipcode',
        'workplace',
        'address',
        'linen',
        'nearest_laundry',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
