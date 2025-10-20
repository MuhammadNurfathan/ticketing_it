<?php

namespace App\Models;
use Illuminate\database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHeader extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'project_header';
    protected $fillable = [
        'project_code',
        'project_name',
        'request_date',
        'description',
        'requestor_id',
        'dev_id',
        'priority_id',
        'status_id',
        'progress_percent',
        'progress_date',
        'start_date',
        'end_date'
    ];

    protected $date = [
        'progress_date',
        'start_date',
        'end_date',
        'deleted_at'
    ];

    public function details (){
        return $this->hasMany(ProjectDetail::class,'project_header_id','id');
    }

    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }

    public function requestor(){
        return $this->belongsTo(User::class,'requestor_id');
    }

    public function developer(){
        return $this->belongsTo(User::class,'dev_id');
    }

    public function scopeBetweenRequestDates($query, $start, $end)
    {
        if ($start && $end) {
            $start = date('Y-m-d 00:00:00', strtotime($start));
            $end   = date('Y-m-d 23:59:59', strtotime($end));
            return $query->whereBetween('request_date', [$start, $end]);
        }
        return $query;
    }
}
