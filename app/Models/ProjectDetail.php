<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $table = 'project_details';

    protected $fillable = [
        'project_header_id',
        'progress_date',
        'description',
        'status_id',
        'progress_percent',
        'developer_id',
    ];

    protected $casts = [
        'progress_date' => 'datetime',
    ];

    public function header()
    {
        return $this->belongsTo(ProjectHeader::class, 'project_header_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')
            ->where('context', 'project');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public static function logProgress($project, $data, $statusId)
    {
        self::create([
            'project_header_id' => $project->id,
            'progress_date' => $data['progress_date'],
            'progress_percent' => $data['progress_percent'],
            'status_id' => $statusId,
            'description' => $data['description'] ?? null,
            'developer_id' => $data['developer_id'] ?? null,
        ]);
    }

    public static function logDone($project, $data, $statusId, $apply)
    {
        self::create([
            'project_header_id' => $project->id,
            'progress_date' => now(),
            'progress_percent' => 100,
            'status_id' => $statusId,
            'description' => 'DONE. Applied=' . ($apply ? 'YES' : 'NO'),
            'developer_id' => $data['developer_id'] ?? auth()->id,
        ]);
    }

    public static function logPending($project, $data, $statusId)
    {
        self::create([
            'project_header_id' => $project->id,
            'progress_date' => now(),
            'progress_percent' => $project->progress_percent ?? 0,
            'status_id' => $statusId,
            'description' => 'PENDING: ' . $data['reason'],
            'developer_id' => $data['developer_id'],
        ]);
    }

    public static function logContinue($project, $pending, $data, $statusId)
    {
        self::create([
            'project_header_id' => $project->id,
            'progress_date' => now(),
            'progress_percent' => $project->progress_percent ?? 0,
            'status_id' => $statusId,
            'description' => 'CONTINUE. Durasi=' . $pending->duration_minutes,
            'developer_id' => $data['developer_id'],
        ]);
    }
}
