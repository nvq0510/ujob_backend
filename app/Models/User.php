<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Các thuộc tính có thể điền (mass assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'zipcode',
        'address',
        'role',
        'status',
    ];

    /**
     * Ẩn các trường khỏi JSON trả về.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các casting (kiểu dữ liệu) cho các trường.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
