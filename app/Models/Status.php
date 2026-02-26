<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'name',
        'type',
        'context',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function projects()
    {
        return $this->hasMany(ProjectHeader::class);
    }

    public function scopeContext($query, string $context)
    {
        return $query->where('context', $context);
    }

}