<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketDoneNotification;

use App\Http\Requests\Tickets\TicketStoreRequest;
use App\Http\Requests\Tickets\TicketUpdateRequest;
use App\Http\Requests\Tickets\TicketUserUpdateRequest;
use App\Http\Requests\Tickets\TicketStatusRequest;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        // ✅ Realtime stats (tanpa filter)
        $statsRealtime = Ticket::stats(null, null);

        // ✅ History stats (ikut filter tanggal)
        $statsHistory  = Ticket::stats($start, $end);

        // ✅ Gabung sesuai kebutuhan card
        $stats = [
            'waiting'     => $statsRealtime['waiting'] ?? 0,
            'in_progress' => $statsRealtime['in_progress'] ?? 0,
            'done'        => $statsHistory['done'] ?? 0,
            'void'        => $statsHistory['void'] ?? 0,
        ];

        // ✅ History base (difilter tanggal)
        $historyBase = Ticket::betweenRequestDates($start, $end);

        $doneTickets = (clone $historyBase)
            ->whereHas('status', fn($q) => $q->whereIn('name', ['Done', 'Feedback']))
            ->with('feedback')
            ->orderBy('updated_at', 'desc')
            ->get();

        $voidTickets = (clone $historyBase)
            ->void()
            ->orderBy('updated_at', 'desc')
            ->get();

        // ✅ Realtime list (tanpa filter)
        $waitingTickets    = Ticket::waiting()->orderBy('ticket_code', 'asc')->get();
        $inProgressTickets = Ticket::inProgress()->orderBy('ticket_code', 'asc')->get();

        return view('tickets/DashboardTicketsAdmin', compact(
            'stats',
            'waitingTickets',
            'inProgressTickets',
            'doneTickets',
            'voidTickets',
            'start',
            'end',
        ));
    }

    public function indexUser()
    {
        $userId = Auth::id();

        $myTicket = Ticket::where('user_id', $userId)
            ->orderByRaw("
                CASE 
                    WHEN status_id = 3 THEN 1
                    WHEN status_id IN (1,2) THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('request_date')
            ->get();

        $data = Ticket::formData();

        $hasDoneWithoutFeedback = Ticket::where('user_id', $userId)
            ->where('status_id', 3)
            ->doesntHave('feedback')
            ->exists();

        $view = view('tickets.DashboardTicketsUser', [
            'myTicket'              => $myTicket,
            'hasDoneWithoutFeedback' => $hasDoneWithoutFeedback,
            'users'                 => $data['users'],
            'assets'                => $data['assets'],
            'categories'            => $data['categories'],
        ]);

        return $hasDoneWithoutFeedback
            ? $view->with('error', '⚠️ BERIKAN FEEDBACK TERLEBIH DAHULU.')
            : $view;
    }

    public function create()
    {
        $data = Ticket::formData();

        return view('tickets.CreateAdmin', [
            'users'      => $data['users'],
            'assets'     => $data['assets'],
            'statuses'   => $data['statuses'],
            'categories' => $data['categories'],
            'supports'   => $data['supports'],
            'priorities' => $data['priorities'],
        ]);
    }

    public function createUser()
    {
        $user = Auth::user();

        $hasDoneWithoutFeedback = Ticket::where('user_id', $user->id)
            ->where('status_id', 3)
            ->doesntHave('feedback')
            ->exists();

        if ($hasDoneWithoutFeedback) {
            return redirect()->route('DashboardTicketsUser.index')
                ->with('error', '⚠️  BERIKAN FEEDBACK TERLEBIH DAHULU');
        }

        $data = Ticket::formData();

        return view('tickets.CreateUser', [
            'users'      => $data['users'],
            'categories' => $data['categories'],
        ]);
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['user', 'support', 'category', 'asset', 'priority', 'status'])->findOrFail($id);
        $data = Ticket::formData();

        return view('tickets.EditAdmin', [
            'ticket'     => $ticket,
            'users'      => $data['users'],
            'assets'     => $data['assets'],
            'statuses'   => $data['statuses'],
            'categories' => $data['categories'],
            'supports'   => $data['supports'],
            'priorities' => $data['priorities'],
        ]);
    }

    public function store(TicketStoreRequest $request)
    {
        $validated  = $request->validated();
        $isFromUser = ($validated['from'] ?? null) === 'user';

        $validated['ticket_code']  = Ticket::generateTicketCode();
        $validated['request_date'] = now();
        $validated['waiting_hour'] = 0;
        $validated['status_id'] = $data['status_id'] ?? 1;
        // set late kalau time_spent ada
        $validated['is_late'] = isset($validated['time_spent']) && (int)$validated['time_spent'] > 480;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tickets', 'public');
        }

        $ticket = Ticket::create($validated);

        if (!$isFromUser && (int)$ticket->status_id === 3) {
            $this->sendDoneNotification($ticket);
        }

        $route = $isFromUser ? 'DashboardTicketsUser.index' : 'DashboardTicketsAdmin.index';

        return redirect()->route($route)->with('success', '✅ Ticket berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $ticket = Ticket::with(['user', 'support', 'category', 'asset', 'priority', 'status'])->findOrFail($id);
        $data = Ticket::formData();

        return view('tickets.EditUser', [
            'ticket'     => $ticket,
            'users'      => $data['users'],
            'categories' => $data['categories'],
        ]);
    }

    public function updateUser(TicketUserUpdateRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->validated());

        return redirect()->route('DashboardTicketsUser.index')
            ->with('success', '✅ Ticket berhasil diupdate!');
    }

    public function update(TicketUpdateRequest $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validated();

        // jangan ubah assets_id kalau tidak dikirim
        if (!$request->filled('assets_id')) {
            unset($validated['assets_id']);
        }

        $statusId = (int) $validated['status_id'];

        // hitung waiting_hour saat status keluar dari waiting
        if ($statusId !== 1 && $ticket->request_date) {
            $validated['waiting_hour'] = $ticket->request_date->diffInMinutes(now(), true);
        }

        // update late flag
        if (isset($validated['time_spent'])) {
            $validated['is_late'] = (int)$validated['time_spent'] > 480;
        } else {
            $validated['is_late'] = $ticket->is_late ?? false;
        }

        $ticket->update($validated);

        if ($statusId === 3) {
            $this->sendDoneNotification($ticket);
        }

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', '✅ Ticket berhasil diupdate!');
    }

    public function updateStatus(TicketStatusRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        // kalau status void (misal id=4), notes wajib di form — tapi di sini minimal 5 sudah di request
        $data = [
            'status_id' => $validated['status_id'],
        ];

        if (!empty($validated['notes'])) {
            $data['notes'] = $validated['notes'];
        }

        $ticket->update($data);

        if ((int)$validated['status_id'] === 3) {
            $this->sendDoneNotification($ticket);
        }

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function updateStatusDone(TicketStatusRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        $statusId = (int) $validated['status_id'];

        // solution wajib kalau done (biar aman, enforce di controller tipis)
        if ($statusId === 3 && empty($validated['solution'])) {
            return back()->withErrors(['solution' => 'Solution wajib diisi'])->withInput();
        }

        $timeSpent = $validated['time_spent'] ?? $ticket->time_spent ?? 0;

        $update = [
            'status_id'  => $statusId,
            'time_spent' => $timeSpent,
            'is_late'    => (int)$timeSpent > 480,
        ];

        if (!empty($validated['solution'])) $update['solution'] = $validated['solution'];
        if (!empty($validated['notes']))    $update['notes']    = $validated['notes'];

        if ($statusId === 3) {
            $update['end_date'] = now();
        }

        $ticket->update($update);

        if ($statusId === 3) {
            $this->sendDoneNotification($ticket);
        }

        return back()->with('success', 'Ticket updated successfully.');
    }

    private function sendDoneNotification(Ticket $ticket): void
    {
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(new TicketDoneNotification($ticket));
        }
    }
}
