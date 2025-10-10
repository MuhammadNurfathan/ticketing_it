<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Status;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        // Get filter dari request (optional)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $date = $request->input('date');

        // ==================== STATISTICS ====================

        $waitingTicketsCount = Ticket::waiting()->count();
        $inProgressTicketsCount = Ticket::inProgress()->count();
        $completedThisMonthCount = Ticket::completed()->thisMonth()->count();

        // ==================== TICKETS DATA ====================

        $ticketsInProgress = Ticket::inProgress()->latest()->get();
        $ticketsWaiting = Ticket::waiting()->latest()->get();
        $ticketsClosedThisMonth = Ticket::completed()->thisMonth()->latest()->get();

        // ==================== KPI METRICS ====================

        // Average Response Wait Time
        $avgResponseWaitTime = Ticket::completed()
            ->thisMonth()
            ->avg('waiting_hour');
        $avgResponseWaitTime = $avgResponseWaitTime ? round($avgResponseWaitTime, 2) : 0;

        // Full Resolution Time (request -> end)
        $fullResolutionTime = Ticket::completed()
            ->thisMonth()
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, request_date, end_date)) as avg_time')
            ->value('avg_time');
        $fullResolutionTime = $fullResolutionTime ? round($fullResolutionTime, 2) : 0;

        // Average Resolution Time
        $avgResolutionTime = Ticket::completed()
            ->thisMonth()
            ->avg('time_spent');
        $avgResolutionTime = $avgResolutionTime ? round($avgResolutionTime, 2) : 0;

        // SLA Meet (selesai <= 48 jam)
        $totalCompletedThisMonth = Ticket::completed()->thisMonth()->count();
        $metSLA = Ticket::completed()
            ->thisMonth()
            ->where('time_spent', '<=', 48)
            ->count();
        $slaPercentage = $totalCompletedThisMonth > 0
            ? round(($metSLA / $totalCompletedThisMonth) * 100, 2)
            : 0;

        // ==================== RETURN VIEW ====================

        return view('DashboardTicket', compact(
            'waitingTicketsCount',
            'inProgressTicketsCount',
            'completedThisMonthCount',
            'ticketsInProgress',
            'ticketsWaiting',
            'ticketsClosedThisMonth',
            'avgResponseWaitTime',
            'fullResolutionTime',
            'avgResolutionTime',
            'slaPercentage'
        ));
    }

    public function assignTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'support_id' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'support_id' => $request->support_id,
            'status_id' => Status::where('status_name', 'In Progress')->first()?->id,
            'start_date' => now(),
        ]);

        if ($ticket->request_date) {
            $waitingHours = $ticket->request_date->diffInHours($ticket->start_date);
            $ticket->update(['waiting_hours' => $waitingHours]);
        }

        return redirect()->back()->with('success', 'Ticket berhasil di-assign!');
    }

    public function pendTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::where('status_name', 'On Hold')->first()?->id,
        ]);

        return redirect()->back()->with('success', 'Ticket di-pending!');
    }

    public function completeTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'solution' => 'required|string|min:10',
        ]);

        $endDate = now();

        // Hitung time spent (start -> end, fallback ke request_date)
        $timeSpent = 0;
        if ($ticket->start_date) {
            $timeSpent = $ticket->start_date->diffInHours($endDate);
        } elseif ($ticket->request_date) {
            $timeSpent = $ticket->request_date->diffInHours($endDate);
        }

        $ticket->update([
            'solution' => $request->solution,
            'status_id' => Status::where('status_name', 'Completed')->first()?->id,
            'end_date' => $endDate,
            'time_spent' => $timeSpent,
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil diselesaikan!');
    }

    public function cancelTicket(Ticket $ticket)
    {
        $ticket->update([
            'status_id' => Status::where('status_name', 'Cancelled')->first()?->id,
        ]);

        return redirect()->back()->with('success', 'Ticket dibatalkan!');
    }
}
