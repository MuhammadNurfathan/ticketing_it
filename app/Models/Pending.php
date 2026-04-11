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
        'count_to_effective',
        'duration_override',
    ];

    protected $casts = [
        'pending_start' => 'datetime',
        'pending_end'   => 'datetime',
    ];

    public function projectHeader()
    {
        return $this->belongsTo(ProjectHeader::class, 'project_header_id');
    }

    public function closePending($data)
{
    $this->pending_end = now();

    $auto = now()->diffInMinutes($this->pending_start);
    $useOverride = (int)$data['use_override'] === 1;

    $minutes = $useOverride ? (int)($data['duration_override'] ?? 0) : $auto;

    $this->duration_minutes = abs($minutes);
    $this->duration_override = $useOverride ? $minutes : null;

    $this->save();
}
}
