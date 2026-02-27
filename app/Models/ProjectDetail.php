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
}