<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes,HasApiTokens;
    protected $fillable = 
    [
        'name',
        'username',
        'department_id',
        'role_id',
        'email',
        'phone',
        'job_position',
        'password',
    ];
    protected $hidden = 
    [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }
    public function role()
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }
}
