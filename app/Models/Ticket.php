<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Ticket extends Model
{

    protected $table = 'tickets';

    protected $fillable = [
        'ticket_code',
        'user_id',
        'support_id',
        'category_id',
        'asset_id',
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
        'nama_pembuat',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'is_late'      => 'boolean',
    ];

    /* =======================
     | RELATIONS
     ======================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function support()
    {
        return $this->belongsTo(User::class, 'support_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function asset()
    {
        return $this->belongsTo(Assets::class, 'assets_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->where('context', 'ticket');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'ticket_id');
    }

    /* =======================
     | SCOPES
     ======================= */

    public function scopeBetweenRequestDates($query, $start, $end)
    {
        if ($start && $end) {
            return $query->whereBetween('request_date', [
                "$start 00:00:00",
                "$end 23:59:59"
            ]);
        }

        return $query;
    }

    public function scopeByStatusName($query, $status)
    {
        return $query->whereHas('status', function ($q) use ($status) {
            $q->where('name', $status);
        });
    }

    public function scopeWaiting($query)
    {
        return $query->whereHas(
            'status',
            fn($q) =>
            $q->where('type', 'waiting')
        );
    }

    public function scopeInProgress($query)
    {
        return $query->whereHas(
            'status',
            fn($q) =>
            $q->where('type', 'in_progress')
        );
    }

    public function scopeDone($query)
    {
        return $query->whereHas(
            'status',
            fn($q) =>
            $q->where('type', 'done')
        );
    }

    public function scopeVoid($query)
    {
        return $query->whereHas(
            'status',
            fn($q) =>
            $q->where('type', 'void')
        );
    }


    /* =======================
     | BUSINESS LOGIC
     ======================= */

    public static function generateTicketCode(): string
    {
        $last = self::latest('id')->first();

        $number = $last
            ? str_pad(((int) substr($last->ticket_code, 4)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        return "TCK-{$number}";
    }

    public static function formData(): array
    {
        return [
            'locations'  => Location::all(),
            'users'      => User::all(),
            'assets'     => Assets::all(),
            'statuses'   => Status::where('context', 'ticket')->get(),
            'categories' => Category::all(),
            'supports'   => User::where('role_id', 1)->get(),
            'priorities' => Priority::all(),
        ];
    }

    /* =======================
     | STATISTICS
     ======================= */

    public static function stats($start = null, $end = null): array
    {
        $query = self::query()->betweenRequestDates($start, $end);

        return [
            'waiting'     => (clone $query)->waiting()->count(),
            'in_progress' => (clone $query)->inProgress()->count(),
            'done'        => (clone $query)->done()->count(),
            'void'        => (clone $query)->void()->count(),
        ];
    }

    public static function statistik($query): array
    {
        $SLA_MINUTES = 480;

        $waiting    = (clone $query)->waiting()->count();
        $inProgress = (clone $query)->inProgress()->count();
        $done       = (clone $query)->done()->count();
        $void       = (clone $query)->void()->count();

        $totalValid = $waiting + $inProgress + $done;

        $avgWaiting   = round((clone $query)->avg('waiting_hour'), 2);
        $avgTimeSpent = round((clone $query)->avg('time_spent'), 2);
        $sumTimeSpent = (clone $query)->sum('time_spent');

        $solvedInSLA = (clone $query)
            ->done()
            ->where('time_spent', '<=', $SLA_MINUTES)
            ->count();

        $sla = $totalValid > 0
            ? round(($solvedInSLA / $totalValid) * 100, 2)
            : 0;

        return [
            'avg_waiting'    => $avgWaiting,
            'avg_time_spent' => $avgTimeSpent,
            'sum_time_spent' => $sumTimeSpent,
            'sla'            => $sla,
        ];
    }
}
