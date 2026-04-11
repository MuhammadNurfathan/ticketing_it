<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketDoneNotification;

class TicketService
{
    public function getAdminDashboard($request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $statsRealtime = Ticket::stats(null, null);
        $statsHistory  = Ticket::stats($start, $end);

        $stats = [
            'waiting'     => $statsRealtime['waiting'] ?? 0,
            'in_progress' => $statsRealtime['in_progress'] ?? 0,
            'done'        => $statsHistory['done'] ?? 0,
            'void'        => $statsHistory['void'] ?? 0,
        ];

        $historyBase = Ticket::betweenRequestDates($start, $end);

        return [
            'stats' => $stats,
            'waitingTickets' => Ticket::waiting()->get(),
            'inProgressTickets' => Ticket::inProgress()->get(),
            'doneTickets' => (clone $historyBase)->done()->with('feedback')->get(),
            'voidTickets' => (clone $historyBase)->void()->get(),
            'start' => $start,
            'end' => $end,
        ];
    }

    public function getUserDashboard($userId)
    {
        $tickets = Ticket::getUserTickets($userId);
        $data = Ticket::formData();

        $hasFeedback = Ticket::hasDoneWithoutFeedback($userId);

        return array_merge($data, [
            'myTicket' => $tickets,
            'hasDoneWithoutFeedback' => $hasFeedback
        ]);
    }

    public function getFormData()
    {
        return Ticket::formData();
    }

    public function handleCreateUser($userId)
    {
        if (Ticket::hasDoneWithoutFeedback($userId)) {
            return redirect()->route('DashboardTicketsUser.index')
                ->with('error', '⚠️ BERIKAN FEEDBACK TERLEBIH DAHULU');
        }

        return view('tickets.CreateUser', Ticket::formData());
    }

    public function store($request)
    {
        $data = $request->validated();

        $data['ticket_code'] = Ticket::generateTicketCode();
        $data['request_date'] = now();
        $data['status_id'] = $data['status_id'] ?? 1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets', 'public');
        }

        $ticket = Ticket::create($data);

        if ($ticket->status_id == 3) {
            $this->sendDoneNotification($ticket);
        }
    }

    public function edit($id)
    {
        return array_merge(
            ['ticket' => Ticket::withAll()->findOrFail($id)],
            Ticket::formData()
        );
    }

    public function editUser($id)
    {
        return [
            'ticket' => Ticket::withAll()->findOrFail($id),
        ];
    }

    public function updateUser($id, $data)
    {
        Ticket::findOrFail($id)->update($data);
    }

    public function update($id, $data)
    {
        $ticket = Ticket::findOrFail($id);

        if (!isset($data['assets_id'])) {
            unset($data['assets_id']);
        }

        if ($ticket->request_date) {
            $data['waiting_hour'] = $ticket->request_date->diffInMinutes(now());
        }

        if (isset($data['time_spent'])) {
            $data['is_late'] = $data['time_spent'] > 480;
        }

        $ticket->update($data);

        if (($data['status_id'] ?? null) == 3) {
            $this->sendDoneNotification($ticket);
        }
    }

    public function updateStatus($ticket, $data)
    {
        $ticket->update($data);

        if ($data['status_id'] == 3) {
            $this->sendDoneNotification($ticket);
        }
    }

    public function updateStatusDone($ticket, $data)
    {
        $ticket->update([
            'status_id' => $data['status_id'],
            'time_spent' => $data['time_spent'] ?? 0,
            'is_late' => ($data['time_spent'] ?? 0) > 480,
            'solution' => $data['solution'] ?? null,
            'notes' => $data['notes'] ?? null,
            'end_date' => now(),
        ]);

        $this->sendDoneNotification($ticket);
    }

    private function sendDoneNotification($ticket)
    {
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(new TicketDoneNotification($ticket));
        }
    }
}