<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
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
        'start_date',           // ✅ TAMBAHIN INI
        'end_date',             // ✅ TAMBAHIN INI
        'actual_start_date',
        'actual_end_date',
        'effective_end_date',
        'is_late',
        'total_pending_minutes',
        'notes'
    ];

    // ✅ GANTI $date (typo) jadi $casts
    protected $casts = [
        'request_date' => 'datetime',
        'start_date' => 'datetime',        // ✅ TAMBAHIN INI
        'end_date' => 'datetime',          // ✅ TAMBAHIN INI
        'actual_start_date' => 'datetime',
        'actual_end_date' => 'datetime',
        'effective_end_date' => 'datetime',
        'is_late' => 'boolean',
    ];

    public function developer()
{
    return $this->belongsTo(User::class, 'dev_id')->where('role_id', 1);
}


    public function details()
    {
        return $this->hasMany(ProjectDetail::class, 'project_header_id', 'id')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')->withTrashed();
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id')->withTrashed();
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id')->withTrashed();
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
    $baseQuery = self::query();

    // ======================
    // STATUS REAL-TIME (NO YEAR)
    // ======================
    $active  = (clone $baseQuery)->where('status_id', 2)->count(); // In Progress
    $waiting = (clone $baseQuery)->where('status_id', 1)->count();
    $pending = (clone $baseQuery)->where('status_id', 6)->count();
    $void    = (clone $baseQuery)->where('status_id', 4)->count();

    // ======================
    // TOTAL PROJECT AKTIF DI TAHUN (OVERLAP)
    // ======================
    $totalQuery = self::query();

    if ($year) {
        $totalQuery
            ->whereDate('start_date', '<=', "$year-12-31")
            ->where(function ($q) use ($year) {
                $q->whereNull('effective_end_date')
                  ->orWhereDate('effective_end_date', '>=', "$year-01-01");
            });
    }

    $total = $totalQuery
        ->where('status_id', '!=', 5) // exclude cancelled (kalau ada)
        ->count();

    // ======================
    // CLOSED & SLA (BY YEAR)
    // ======================
    $closedQuery = self::query();

    if ($year) {
        $closedQuery->whereYear('effective_end_date', $year);
    }

    $closed = (clone $closedQuery)->where('status_id', 3)->count();

    $closedOnTime = (clone $closedQuery)
        ->where('status_id', 3)
        ->where('is_late', 0)
        ->count();

    $closedLate = (clone $closedQuery)
        ->where('status_id', 3)
        ->where('is_late', 1)
        ->count();

    $sla = $closed > 0
        ? round(($closedOnTime / $closed) * 100, 2)
        : 0;

    return [
        'total'         => $total,
        'active'        => $active,
        'waiting'       => $waiting,
        'pending'       => $pending,
        'void'          => $void,
        'closed'        => $closed,
        'closedOnTime'  => $closedOnTime,
        'closedLate'    => $closedLate,
        'sla'           => $sla,
    ];
}



}
