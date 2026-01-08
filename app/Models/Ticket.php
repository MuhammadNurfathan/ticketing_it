<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable =
    [
        'ticket_code',
        'user_id',
        'support_id',
        'problem_category_id',
        'assets_id',
        'status_id',
        'priority_id',
        'problem',
        'image',
        'solution',
        'notes',
        'request_date',
        'waiting_hour',
        'start_date',
        'end_date',
        'time_spent',
        'is_late',
        'updated_at',
        'created_at',
        'nama_pembuat'
    ];

    protected $casts =
    [
        'request_date' => 'datetime',
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    public function support()
    {
        return $this->belongsTo(User::class, 'support_id')->withTrashed();
    }
    public function problemCategory()
    {
        return $this->belongsTo(ProblemCategory::class)->withTrashed();
    }
    public function assets()
    {
        return $this->belongsTo(Assets::class)->withTrashed();
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class)->withTrashed();
    }
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'ticket_id')->withTrashed();
    }

    // ==================== SCOPES ====================
    public function scopeBetweenRequestDates($query, $start, $end)
    {
        if ($start && $end) {
            $start = date('Y-m-d 00:00:00', strtotime($start));
            $end   = date('Y-m-d 23:59:59', strtotime($end));
            return $query->whereBetween('request_date', [$start, $end]);
        }
        return $query;
    }

    // ==================== FILTER TABLE BY STATUS ====================
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
        return $query->whereHas('status', function ($q) {
             $q->whereIn('id', [3,5]);
        });
    }
    public function scopeVoid($query)
    {
        return $this->scopeByStatus($query, 'Void');
    }

    // ==================== GENERATE TICKET CODE (Called during store) ====================
    public static function generateTicketCode()
    {
        $lastTicket = self::latest('id')->first();

        if ($lastTicket) {
            $lastNumber = (int)substr($lastTicket->ticket_code, 4);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "TCK-{$newNumber}";
    }

    // ==================== FUNGSI GET DATA ====================
    public static function data()
    {
        // Ambil data untuk form
        $locations  = Location::all();
        $users      = User::all();
        $assets     = Assets::all();
        $statuses   = Status::all();
        $categories = ProblemCategory::all();
        $developers = User::where('role_id', 1)->get();
        $priorities = Priority::all();

        return [
            'locations'       => $locations,
            'users'           => $users,
            'assets'          => $assets,
            'statuses'        => $statuses,
            'categories'      => $categories,
            'developers'      => $developers,
            'priorities'      => $priorities,
        ];
    }

    // ==================== STATISTIK KHUSUS: HANYA DONE YANG DIFILTER ====================
    public static function getStatsFiltered($start = null, $end = null)
    {
        // Hitung status lain TANPA filter tanggal
        $waitingCount    = self::waiting()->count();
        $inProgressCount = self::inProgress()->count();
        $voidCount       = self::void()->count();

        // DONE difilter berdasarkan request_date
        $doneCount = self::done()->betweenRequestDates($start, $end)->count();

        return [
            'waiting'     => $waitingCount,
            'in_progress' => $inProgressCount,
            'done'        => $doneCount,
            'void'        => $voidCount,
        ];
    }

  public static function Statistik($query)
{
    // ======================== Konstanta ========================
    $SLA_MINUTES = 480; // 8 jam = 480 menit

    // ======================== Status Count ========================
    $waiting    = (clone $query)->waiting()->count();
    $inProgress = (clone $query)->inProgress()->count();
    $done       = (clone $query)->done()->count();
    $void       = (clone $query)->void()->count();

    $totalAll   = $waiting + $inProgress + $done + $void;
    $totalValid = $waiting + $inProgress + $done; // void tidak dihitung SLA

    // ======================== Statistik Waktu ========================
    $avgWaiting   = round((clone $query)->avg('waiting_hour'), 2); // menit / jam sesuai field
    $avgTimeSpent = round((clone $query)->avg('time_spent'), 2);   // MENIT
    $sumTimeSpent = (clone $query)->sum('time_spent');             // MENIT

    // ======================== SLA ========================
    $solvedInSLA = (clone $query)
        ->done()
        ->where('time_spent', '<=', $SLA_MINUTES)
        ->count();

    $slaPercent = $totalValid > 0
        ? round(($solvedInSLA / $totalValid) * 100, 2)
        : 0;

    // ======================== Return ========================
    return [
        'avg_waiting'     => $avgWaiting,
        'avg_time_spent'  => $avgTimeSpent,
        'sum_time_spent'  => $sumTimeSpent,
        'sla'             => $slaPercent,
    ];
}


 
}
