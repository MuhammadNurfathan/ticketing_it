<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    protected $table = 'pending';

    protected $fillable = [
        'project_header_id',
        'pending_start',
        'pending_end',
        'reason',
        'duration_minutes',
    ];

    protected $casts = [
        'pending_start' => 'datetime',
        'pending_end'   => 'datetime',
    ];

    public function projectHeader()
    {
        return $this->belongsTo(ProjectHeader::class, 'project_header_id');
    }
}