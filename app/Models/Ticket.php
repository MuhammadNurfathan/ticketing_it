<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    // ==================== FILLABLE ====================
    // Field-field yang bisa diisi massal (mass assignment)
    protected $fillable = [
        'ticket_code',
        'user_id',
        'support_id',
        'problem_category_id',
        'assets_id',
        'status_id',
        'priority_id',
        'problem',
        'solution',
        'request_date',
        'start_date',
        'end_date',
        'waiting_hour',
        'time_spent',
    ];

    // ==================== CASTS ====================
    // Otomatis konversi ke tipe data tertentu saat ambil/masukkan data
    protected $casts = [
        'request_date' => 'datetime',
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================
    // Relasi ke tabel lain
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // User yang membuat tiket
    }

    public function support()
    {
        return $this->belongsTo(User::class, 'support_id'); // User yang ditugaskan
    }

    public function problemCategory()
    {
        return $this->belongsTo(ProblemCategory::class); // Kategori masalah
    }

    public function assets()
    {
        return $this->belongsTo(Assets::class); // Aset terkait tiket
    }

    public function status()
    {
        return $this->belongsTo(Status::class); // Status tiket
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class); // Prioritas tiket
    }

    // ==================== SCOPES ====================
    // Scope untuk filter query

    /**
     * Filter tiket berdasarkan tanggal request
     */
    public function scopeBetweenRequestDates($query, $start, $end)
    {
        if ($start && $end) {
            $start = date('Y-m-d 00:00:00', strtotime($start));
            $end   = date('Y-m-d 23:59:59', strtotime($end));
            return $query->whereBetween('request_date', [$start, $end]);
        }
        return $query;
    }

    /**
     * Filter tiket berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->whereHas('status', fn($q) => $q->where('status_name', $status));
    }

    // Shortcut scope untuk status tertentu
    public function scopeWaiting($query)    { return $this->scopeByStatus($query, 'Waiting'); }
    public function scopePending($query)    { return $this->scopeByStatus($query, 'Pending'); }
    public function scopeInProgress($query) { return $this->scopeByStatus($query, 'In Progress'); }
    public function scopeDone($query)       { return $this->scopeByStatus($query, 'Done'); }
    public function scopeVoid($query)       { return $this->scopeByStatus($query, 'Void'); }

    // ==================== STATISTIK ====================
    /**
     * Hitung statistik tiket berdasarkan tanggal request
     */
    public static function getStatsByRequest($start = null, $end = null)
    {
        return self::calculateStats(self::betweenRequestDates($start, $end));
    }

    /**
     * Hitung statistik tiket berdasarkan tanggal selesai
     */
    public static function getStatsByEndDate($start = null, $end = null)
    {
        return self::calculateStats(self::betweenEndDates($start, $end));
    }

    // ==================== FUNGSI PERHITUNGAN ====================
    /**
     * Fungsi utama untuk menghitung statistik tiket
     *
     * Menghitung:
     * - Total tiket per status
     * - Rata-rata waktu menunggu (avg_waiting)
     * - Rata-rata waktu penyelesaian (avg_time_spent)
     * - Total jam yang dihabiskan (sum_time_spent)
     * - SLA (% tiket selesai <= 8 jam)
     */
    protected static function calculateStats($query)
    {
        $waiting    = (clone $query)->waiting()->count();
        $pending    = (clone $query)->pending()->count();
        $inProgress = (clone $query)->inProgress()->count();
        $done       = (clone $query)->done()->count();
        $void       = (clone $query)->void()->count();

        $totalAll   = $waiting + $pending + $inProgress + $done + $void;
        $totalValid = $waiting + $pending + $inProgress + $done;

        // ======================== Statistik tambahan ========================
        $avgWaiting   = round((clone $query)->avg('waiting_hour'), 2); // rata-rata menunggu
        $avgTimeSpent = round((clone $query)->avg('time_spent'), 2);  // rata-rata penyelesaian
        $sumTimeSpent = (clone $query)->sum('time_spent');            // total jam yang dihabiskan

        $solvedInSLA = (clone $query)->done()->where('time_spent', '<=', 8)->count();
        $slaPercent  = $totalValid > 0 ? round(($solvedInSLA / $totalValid) * 100, 2) : 0;

        return [
            'total'           => $totalAll,
            'waiting'         => $waiting,
            'pending'         => $pending,
            'in_progress'     => $inProgress,
            'done'            => $done,
            'void'            => $void,
            'avg_waiting'     => $avgWaiting,    // rata-rata menunggu
            'avg_time_spent'  => $avgTimeSpent,  // rata-rata penyelesaian
            'sum_time_spent'  => $sumTimeSpent,  // total jam
            'sla'             => $slaPercent,    // persentase SLA
        ];
    }
}
