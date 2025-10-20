<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDetail extends Model
{
  use HasFactory,SoftDeletes;
    protected $table = 'project_detail';
    protected $fillable = [
        'project_header_id',
        'progress_date',
        'memo',
        'status_id',
        'progress_percent',
    ];

    protected $dates = [
        'progress_date',
        'deleted_at',
    ];

    public function header(){
        return $this->belongsTo(ProjectHeader::class, 'project_header_id');
    }

    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }
}
