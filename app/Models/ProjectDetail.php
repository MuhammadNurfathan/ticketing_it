<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory; // ✅ HAPUS SoftDeletes
    
    protected $table = 'project_detail';
    
    protected $fillable = [
        'project_header_id',
        'progress_date',
        'memo',
        'status_id',
        'progress_percent',
        'developer_name',
    ];

    protected $casts = [
        'progress_date' => 'datetime',
    ];

    public function header(){
        return $this->belongsTo(ProjectHeader::class, 'project_header_id')->withTrashed();
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id')->withTrashed();
    }
}