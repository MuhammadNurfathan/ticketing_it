<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_code',
        'user_id',
        'support_id',
        'category_id',
        'inventaris_id',
        'status_id',
        'problem',
        'request_date',
        'solution',
        'start_date',
        'end_date',
        'waiting_hours',
        'time_spent',
        'priority_id',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()  { return $this->belongsTo(User::class, 'user_id'); }
    public function support() { return $this->belongsTo(User::class, 'support_id'); }
    public function category() { return $this->belongsTo(ProblemCategory::class, 'category_id'); }
    public function assets() { return $this->belongsTo(Assets::class); }
    public function status() { return $this->belongsTo(Status::class); }
    public function priority() { return $this->belongsTo(Priority::class); }

    // ==================== SCOPES ====================

    public function scopeWaiting($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('status_name', 'Pending'));
    }

    public function scopeInProgress($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('status_name', 'In Progress'));
    }

    public function scopeCompleted($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('status_name', 'Completed'));
    }

    public function scopeThisMonth($query)
    {
        return $query->where(function ($q) {
            $q->whereMonth('end_date', now()->month)
              ->whereYear('end_date', now()->year);
        })->orWhere(function ($q) {
            $q->whereNull('end_date')
              ->whereMonth('created_at', now()->month)
              ->whereYear('created_at', now()->year);
        });
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    // ==================== HELPERS ====================

    public function isWaiting()  { return $this->status && $this->status->status_name === 'Pending'; }
    public function isInProgress() { return $this->status && $this->status->status_name === 'In Progress'; }
    public function isCompleted()  { return $this->status && $this->status->status_name === 'Completed'; }

    public function getStatusBadgeColor()
    {
        if (!$this->status) return 'gray';

        return match($this->status->status_name) {
            'Pending' => 'yellow',
            'In Progress' => 'blue',
            'Completed' => 'green',
            'Cancelled' => 'red',
            'On Hold' => 'orange',
            default => 'gray',
        };
    }

    public function getPriorityBadgeColor()
    {
        if (!$this->priority) return 'gray';

        return match($this->priority->priority_name) {
            'Low' => 'green',
            'Medium' => 'yellow',
            'High' => 'orange',
            'Critical' => 'red',
            default => 'gray',
        };
    }
}
