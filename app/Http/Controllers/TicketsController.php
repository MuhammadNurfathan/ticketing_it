<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketDoneNotification;
class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $stats = Ticket::getStatsFiltered($start, $end);
        $doneTicketsQuery = Ticket::betweenRequestDates($start, $end);
        $doneTickets = $doneTicketsQuery->whereHas('status', function ($q) {
            $q->whereIn('status_name', ['Done', 'Feedback']);
        })->with('feedback')->orderBy('updated_at', 'desc')->get();
        $waitingTickets = Ticket::waiting()->orderBy('ticket_code', 'asc')->get();
        $inProgressTickets = Ticket::inProgress()->orderBy('ticket_code', 'asc')->get();
        $voidTickets = Ticket::void()->orderBy('updated_at', 'desc')->get();

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
        $myTicket = Ticket::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $data = Ticket::data();
        $users = $data['users'];
        $assets = $data['assets'];
        $categories = $data['categories'];
        $generateticket = $data['generateticket'];
        $hasDoneWithoutFeedback = Ticket::where('user_id', $userId)->where('status_id', 3)->doesntHave('feedback')->exists();
        $hasDoneTicket = $hasDoneWithoutFeedback;

        $view = view('tickets.DashboardTicketsUser', compact(
            'myTicket',
            'hasDoneTicket',
            'users',
            'assets',
            'categories',
            'generateticket'
        ));

        if ($hasDoneWithoutFeedback) {
            return $view->with('error', '⚠️ BERIKAN FEEDBACK TERLEBIH DAHULU.');
        }

        return $view;
    }

    public function create()
    {
        $data = Ticket::data();
        return view(
            'tickets.Create',
            [
                'users'      => $data['users'],
                'assets'     => $data['assets'],
                'statuses'   => $data['statuses'],
                'categories' => $data['categories'],
                'developers' => $data['developers'],
                'priorities' => $data['priorities'],
                'generateticket' => $data['generateticket'],
            ]

        );
    }

    public function createUser()
    {
        $user = Auth::user();
        $hasDoneWithoutFeedback = Ticket::where('user_id', $user->id)->where('status_id', 3)->exists();

        if ($hasDoneWithoutFeedback) {
            return redirect()->route('DashboardTicketsUser.indexUser')
                ->with('error', '⚠️  BERIKAN FEEDBACK TERLEBIH DAHULU');
        }
        $data = Ticket::data();

        return view('tickets.createUser', [
            'users'          => $data['users'],
            'assets'         => $data['assets'],
            'categories'     => $data['categories'],
            'generateticket' => $data['generateticket'],
        ]);
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['user', 'support', 'problemCategory', 'assets', 'priority', 'status'])->findOrFail($id);
        $data = Ticket::data();

        return view('tickets.Edit', [
            'ticket'      => $ticket,
            'users'       => $data['users'],
            'assets'      => $data['assets'],
            'statuses'    => $data['statuses'],
            'categories'  => $data['categories'],
            'developers'  => $data['developers'],
            'priorities'  => $data['priorities'],
            'generateticket' => $data['generateticket'],
        ]);
    }

public function store(Request $request)
{
    // Cek apakah dari user atau admin
    $isFromUser = $request->input('from') === 'user';

    // Base rules
    $rules = [
        'ticket_code'         => 'required',
        'user_id'             => 'required|exists:users,id',
        'problem_category_id' => 'required|exists:problem_categories,id',
        'status_id'           => 'required|exists:status,id',
        'problem'             => 'required|string', // bebas, tidak ada minimal
    ];

    $messages = [
        'ticket_code.required'         => 'Ticket code wajib diisi',
        'user_id.required'             => 'User wajib dipilih',
        'problem_category_id.required' => 'Category wajib dipilih',
        'status_id.required'           => 'Status wajib dipilih',
        'problem.required'             => 'Problem wajib diisi',
        'support_id.required'          => 'IT Support wajib dipilih',
        'assets_id.required'           => 'Assets wajib dipilih',
        'priority_id.required'         => 'Priority wajib dipilih',
        'image.mimes'                  => 'Format file harus JPG, PNG, atau MP4',
        'image.max'                    => $isFromUser ? 'Ukuran file maksimal 5MB' : 'Ukuran file maksimal 10MB',
        'start_date.required'          => 'Start Date wajib diisi',
        'end_date.required'            => 'End Date wajib diisi',
        'end_date.after'               => 'End Date harus setelah Start Date',
        'time_spent.required'          => 'Time Spent wajib diisi',
        'time_spent.min'               => 'Time Spent minimal 1 menit',
        'solution.required'            => 'Solution wajib diisi',
        'solution.min'                 => 'Solution minimal 10 karakter',
    ];

    if (!$isFromUser) {
        // Validasi tambahan untuk ADMIN
        $rules['support_id'] = 'required|exists:users,id';
        $rules['assets_id'] = 'required|exists:assets,id';
        $rules['priority_id'] = 'required|exists:priority,id';
        $rules['image'] = 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240'; // 10MB opsional

        // Validasi berdasarkan status
        $statusId = $request->status_id;

        if ($statusId == 2) { // In Progress
            $rules['start_date'] = 'required|date';
        }

        if ($statusId == 3) { // Done
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after:start_date';
            $rules['time_spent'] = 'required|integer|min:1';
            $rules['solution'] = 'required|string|min:10';
        }
    } else {
        // Validasi khusus USER
        $rules['image'] = 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5120'; // 5MB opsional
    }

    // Validasi
    $validated = $request->validate($rules, $messages);

    // Set default values
    $validated['request_date'] = now();
    $validated['waiting_hour'] = 0;
    $validated['is_late'] = isset($validated['time_spent']) && $validated['time_spent'] > 480;

    // Upload file jika ada
    if ($request->hasFile('image')) {
        try {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public');
            $validated['image'] = $path;
        } catch (\Exception $e) {
            return back()->withErrors(['image' => 'Gagal upload file: ' . $e->getMessage()])->withInput();
        }
    }

    // Create ticket
    try {
        $ticket = Ticket::create($validated);
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Gagal menyimpan ticket: ' . $e->getMessage()])->withInput();
    }

    // Kirim notifikasi jika status Done dan admin
    if (!$isFromUser && $request->status_id == 3 && $ticket->user && $ticket->user->email) {
        $ticket->user->notify(
            (new TicketDoneNotification($ticket))->delay(now()->addMinutes(1))
        );
    }

    // Redirect
    $route = $isFromUser ? 'DashboardTicketsUser.indexUser' : 'DashboardTicketsAdmin.index';
    return redirect()->route($route)
        ->with('success', '✅ Ticket berhasil ditambahkan!');
}

 private function sendDoneNotification($ticket)
    {
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(
                (new TicketDoneNotification($ticket))->delay(now()->addMinutes(1))
            );
        }
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $rules = [
            'support_id'  => 'required|exists:users,id',
            'assets_id'   => 'required|exists:assets,id',
            'status_id'   => 'required|exists:status,id',
            'priority_id' => 'required|exists:priority,id',
        ];

        $messages = [
            'support_id.required'  => 'IT Support wajib dipilih',
            'assets_id.required'   => 'Assets wajib dipilih',
            'status_id.required'   => 'Status wajib dipilih',
            'priority_id.required' => 'Priority wajib dipilih',
            'start_date.required'  => 'Start Date wajib diisi',
            'end_date.required'    => 'End Date wajib diisi untuk status Done',
            'end_date.after'       => 'End Date harus setelah Start Date',
            'time_spent.required'  => 'Time Spent wajib diisi',
            'solution.required'    => 'Solution wajib diisi',
            'solution.min'         => 'Solution minimal 10 karakter',
            'notes.min'            => 'Notes minimal 5 karakter',
        ];

        $statusId = $request->status_id;

        // Status "In Progress"
        if ($statusId == 2) {
            $rules['start_date'] = 'required|date';
        }

        // Status "Done"
        if ($statusId == 3) {
            $rules['start_date']  = 'required|date';
            $rules['end_date']    = 'required|date|after:start_date';
            $rules['time_spent']  = 'required|integer|min:1';
            $rules['solution']    = 'required|string|min:10';
        }

        if ($request->has('notes') && !empty($request->notes)) {
            $rules['notes'] = 'nullable|string|min:5';
        }

        $validated = $request->validate($rules, $messages);

        unset($validated['user_id'], $validated['problem'], $validated['image'], $validated['ticket_code'], $validated['problem_category_id']);

        if ($statusId != 1 && $ticket->request_date) {
            $validated['waiting_hour'] = $ticket->request_date->diffInMinutes(now(), true);
        }

        if (isset($validated['time_spent'])) {
            $validated['is_late'] = $validated['time_spent'] > 480;
        } else {
            $validated['is_late'] = $ticket->is_late ?? false;
        }

        // 🔥 Kirim notifikasi DONE
        if ($statusId == 3) {
            $this->sendDoneNotification($ticket);
        }

        try {
            $ticket->update($validated);
            return redirect()->route('DashboardTicketsAdmin.index')
                ->with('success', '✅ Ticket berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal update ticket: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $data = [
            'status_id' => $request->status_id,
        ];

        if ($request->status_id == 4) {
            $data['notes'] = $request->notes;
        }

        $ticket->update($data);

        if ($request->status_id == 3) {
            $this->sendDoneNotification($ticket);
        }

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function updateStatusDone(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status_id'  => 'required|integer',
            'time_spent' => 'nullable|integer',
            'solution'   => 'required|string',
            'notes'      => 'nullable|string',
        ]);

        $timeSpent = $validated['time_spent'] ?? $ticket->time_spent ?? 0;
        $validated['is_late'] = $timeSpent > 480;
        $validated['time_spent'] = $timeSpent;

        $ticket->update($validated);

        if ($validated['status_id'] == 3) {
            $this->sendDoneNotification($ticket);
        }

        return back()->with('success', 'Ticket updated successfully.');
    }
}
