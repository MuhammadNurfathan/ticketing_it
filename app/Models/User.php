<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'department_id',
        'role_id',
        'email',
        'phone',
        'job_position',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function requestedTickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function handledTickets()
    {
        return $this->hasMany(Ticket::class, 'support_id');
    }

    public function requestedProjects()
    {
        return $this->hasMany(ProjectHeader::class, 'requestor_id');
    }

    public function developedProjects()
    {
        return $this->hasMany(ProjectHeader::class, 'dev_id');
    }

    public function scopeDeveloper($query)
    {
        return $query->where('role_id', 1);
    }

    public function scopeManager($query)
    {
        return $query->where('role_id', 2);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
}
