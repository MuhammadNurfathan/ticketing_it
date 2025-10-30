<?php

namespace App\Models;

use Illuminate\database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHeader extends Model
{
    use HasFactory, SoftDeletes;
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
        'end_date',
        'actual_start_date',
        'actual_end_date',
        'effective_end_date',
        'is_late',
        'total_pending_minutes',
        'notes'
    ];

    protected $date = [
        'progress_date',
        'start_date',
        'end_date',
        'deleted_at'
    ];

    public function details()
    {
        return $this->hasMany(ProjectDetail::class, 'project_header_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'dev_id');
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function pendings()
    {
        return $this->hasMany(Pending::class, 'id_project_header');
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

    public function scopeByStatus($query, $status)
    {
        return $query->whereHas('status', fn($q) => $q->where('status_name', $status));
    }

    public function scopeWaiting($query)
    {
        return $this->scopeByStatus($query, 'Waiting');
    }

    public function scopeInProgress($query)
    {
        return $this->scopeByStatus($query, 'In Progress');
    }

    public function scopeDone($query)
    {
        return $this->scopeByStatus($query, 'Done');
    }

    public function scopeVoid($query)
    {
        return $this->scopeByStatus($query, 'Void');
    }
    public function scopePending($query)
    {
        return $this->scopeByStatus($query, 'Pending');
    }

    public static function statistik($start = null, $end = null)
    {
        $waitingCount = self::Waiting()->betweenRequestDates($start, $end)->count();
        $inProgressCount = self::inProgress()->betweenRequestDates($start, $end)->count();
        $doneCount = self::Done()->betweenRequestDates($start, $end)->count();
        $voidCount = self::Void()->betweenRequestDates($start, $end)->count();
        $voidCount = self::Pending()->betweenRequestDates($start, $end)->count();

        return [
            'waiting' => $waitingCount,
            'in_progress' => $inProgressCount,
            'done' => $doneCount,
            'void' => $voidCount,
            'pending' => $voidCount,
        ];
    }



    public static function data()
    {
        $lastproject = self::latest('id')->first();

        if ($lastproject) {
            $lastNumber = (int)substr($lastproject->project_code, 4);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $generateticket = "PRJ-{$newNumber}";

        $users      = User::all();
        $statuses   = Status::all();
        $developers = User::where('role_id', 1)->get();
        $priorities = Priority::all();
        $pendings = Pending::all();

        return [
            'users'           => $users,
            'statuses'        => $statuses,
            'developers'      => $developers,
            'priorities'      => $priorities,
            'generateticket'  => $generateticket,
        ];
    }

public static function summary($year = null)
{
    $query = self::query();

    // filter berdasarkan start_date jika year diberikan
    if ($year) {
        $query->whereYear('start_date', $year);
    }

    // Active = semua yang tidak berstatus "done" (status_id != 3) dan bukan void (status_id != 4)
    $active = (clone $query)->where('status_id', 2)->count();

    // Closed / Done = status_id == 3
    $closed = (clone $query)->where('status_id', 3)->count();
    $closedOnTime = (clone $query)->where('status_id', 3)->where('is_late',0)->count();
    $closedLate = (clone $query)->where('status_id', 3)->where('is_late',1)->count();

    // Total = semua project kecuali pending
    $total = (clone $query)->where('status_id', '!=', 5)->count();
    $waiting = (clone $query)->where('status_id', 1)->count();

    // Void = status_id == 4
    $void = (clone $query)->where('status_id', 4)->count();
    $pending = (clone $query)->where('status_id', 6)->count();

    // SLA: hitung dari yang DONE (status_id == 3)
    $done = $closed;
    $doneOnTime = (clone $query)
        ->where('status_id', 3)
        ->where('is_late', 0)
        ->count();

    $sla = $done > 0 ? round(($doneOnTime / ($done+$pending+$waiting+$active)) * 100, 2) : 0;

    return [
        'active' => $active,
        'closed' => $closed,
        'sla'    => $sla,
        'void'   => $void,
        'total'  => $total,
        'waiting'  => $waiting,
        'waiting'  => $waiting,
        'closedOnTime'  => $closedOnTime,
        'closedLate'  => $closedLate,
    ];
}

}
