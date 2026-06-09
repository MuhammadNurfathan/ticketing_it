<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHeader extends Model
{
    use HasFactory;

    protected $table = 'project_header';

    protected $fillable = [
        'project_code',
        'project_name',
        'request_date',
        'description',
        'requestor_id',
        'dev_id',                 // optional: PIC dev header (kalau dipakai)
        'priority_id',
        'status_id',
        'progress_percent',
        'progress_date',
        'description',
        'start_date',
        'end_date',
        'actual_start_date',
        'actual_end_date',
        'effective_end_date',
        'is_late',
        'total_pending_minutes',
        'notes',
    ];

    protected $casts = [
        'request_date'       => 'datetime',
        'progress_date'      => 'datetime',
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
        'actual_start_date'  => 'datetime',
        'actual_end_date'    => 'datetime',
        'effective_end_date' => 'datetime',
        'is_late'            => 'boolean',
    ];

    /* =======================
     | RELATIONS
     ======================= */

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    // PIC developer header (opsional)
    public function developer()
    {
        return $this->belongsTo(User::class, 'dev_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id')
            ->where('context', 'project');
    }

    public function details()
    {
        return $this->hasMany(ProjectDetail::class, 'project_header_id');
    }

    public function pendings()
    {
        return $this->hasMany(Pending::class, 'project_header_id');
    }

    /* =======================
     | SCOPES
     ======================= */

    public function scopeBetweenRequestDates($query, $start, $end)
    {
        if ($start && $end) {
            return $query->whereBetween('request_date', [
                $start . ' 00:00:00',
                $end . ' 23:59:59'
            ]);
        }

        return $query;
    }

    public function scopeByStatusType($query, string $type)
    {
        return $query->whereHas(
            'status',
            fn($q) => $q->where('type', $type)->where('context', 'project')
        );
    }

    public function scopeWaiting($q)
    {
        return $q->byStatusType('waiting');
    }
    public function scopeInProgress($q)
    {
        return $q->byStatusType('in_progress');
    }
    public function scopeResolved($q)
    {
        return $q->byStatusType('resolved');
    }
    public function scopeVoid($q)
    {
        return $q->byStatusType('void');
    }
    public function scopePending($q)
    {
        return $q->byStatusType('pending');
    }

    /* =======================
     | STATISTIK
     ======================= */

    public static function statistik($start = null, $end = null)
    {
        return [
            'waiting'     => self::waiting()->betweenRequestDates($start, $end)->count(),
            'in_progress' => self::inProgress()->betweenRequestDates($start, $end)->count(),
            'resolved'        => self::resolved()->betweenRequestDates($start, $end)->count(),
            'void'        => self::void()->betweenRequestDates($start, $end)->count(),
            'pending'     => self::pending()->betweenRequestDates($start, $end)->count(),
        ];
    }

    /* =======================
     | DATA FORM
     ======================= */

    public static function data()
    {
        $last = self::latest('id')->first();

        $number = $last
            ? str_pad(((int) substr($last->project_code, 4)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return [
            'users'          => User::all(),
            'statuses'       => Status::where('context', 'project')->get(), // ✅ only project
            'developers'     => User::where('role_id', 1)->get(),
            'priorities'     => Priority::all(),
            'generateticket' => "PRJ-{$number}",
        ];
    }

    /* =======================
     | SUMMARY DASHBOARD (BALIKIN)
     ======================= */

    public static function summary($year = null)
    {
        $base = self::query();

        if ($year) {
            $base->whereDate('start_date', '<=', "$year-12-31")
                ->where(function ($q) use ($year) {
                    $q->whereNull('effective_end_date')
                        ->orWhereDate('effective_end_date', '>=', "$year-01-01");
                });
        }

        // hitung by type (AMAN, ga hardcode ID)
        $total   = (clone $base)->count();
        $active  = (clone $base)->inProgress()->count();
        $waiting = (clone $base)->waiting()->count();
        $pending = (clone $base)->pending()->count();
        $void    = (clone $base)->void()->count();

        $closedQuery = (clone $base)->resolved();
        if ($year) {
            $closedQuery->whereYear('effective_end_date', $year);
        }

        $closed       = (clone $closedQuery)->count();
        $closedOnTime = (clone $closedQuery)->where('is_late', false)->count();
        $closedLate   = (clone $closedQuery)->where('is_late', true)->count();

        $sla = $closed > 0 ? round(($closedOnTime / $closed) * 100, 2) : 0;

        return compact(
            'total',
            'active',
            'waiting',
            'pending',
            'void',
            'closed',
            'closedOnTime',
            'closedLate',
            'sla'
        );
    }

    public static function createProject($data)
{
    return self::create([
        ...$data,
        'request_date' => now(),
        'status_id' => \App\Models\Status::getId('waiting'),
        'progress_percent' => 0,
    ]);
}

    public function appliedPendingMinutes(): int
{
    return (int) $this->pendings()
        ->whereNotNull('duration_minutes')
        ->where('count_to_effective', true)
        ->sum('duration_minutes');
}

public function getTotalPendingMinutes()
{
    return (int) $this->pendings()
        ->whereNotNull('duration_minutes')
        ->sum('duration_minutes');
}

public function getAppliedPendingMinutes()
{
    return (int) $this->pendings()
        ->whereNotNull('duration_minutes')
        ->where('count_to_effective', true)
        ->sum('duration_minutes');
}

public function calculateEffectiveEnd($minutes)
{
    return $this->end_date
        ? \Carbon\Carbon::parse($this->end_date)->addMinutes($minutes)
        : null;
}

public function updateProgressData($data, $statusId, $pendingMinutes)
{
    $this->update([
        'progress_percent' => $data['progress_percent'],
        'status_id' => $statusId,
        'progress_date' => $data['progress_date'],
        'description' => $data['description'] ?? $this->description,
        'total_pending_minutes' => $pendingMinutes,
        'effective_end_date' => $this->calculateEffectiveEnd($pendingMinutes),
    ]);
}

public function updateStatusData($data)
{
    $this->update([
        'status_id' => $data['status_id'],
        'notes' => $data['notes'] ?? null,
    ]);
}
}
