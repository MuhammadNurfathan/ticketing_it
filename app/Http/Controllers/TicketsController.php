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
        $myTicket = Ticket::where('user_id', $userId)->latest()->get();

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
            'tickets.CreateAdmin',
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

        return view('tickets.CreateUser', [
            'users'          => $data['users'],
            'categories'     => $data['categories'],
            'generateticket' => $data['generateticket'],
        ]);
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['user', 'support', 'problemCategory', 'assets', 'priority', 'status'])->findOrFail($id);
        $data = Ticket::data();

        return view('tickets.EditAdmin', [
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
        $isFromUser = $request->input('from') === 'user';

        $rules = [
            'ticket_code'         => 'required',
            'user_id'             => 'required|exists:users,id',
            'problem_category_id' => 'required|exists:problem_categories,id',
            'status_id'           => 'required|exists:status,id',
            'problem'             => 'required|string',
            'assets_id' => 'nullable|integer',

        ];

        $messages = [
            'ticket_code.required'         => 'Ticket code wajib diisi',
            'user_id.required'             => 'User wajib dipilih',
            'problem_category_id.required' => 'Category wajib dipilih',
            'status_id.required'           => 'Status wajib dipilih',
            'problem.required'             => 'Problem wajib diisi',
            'support_id.required'          => 'IT Support wajib dipilih',
            'priority_id.required'         => 'Priority wajib dipilih',
            'image.mimes'                  => 'Format file harus JPG, PNG, atau MP4',
            'image.max'                    => $isFromUser ? 'Ukuran file maksimal 5MB' : 'Ukuran file maksimal 10MB',
            'start_date.required'          => 'Start Date wajib diisi',
            'end_date.required'            => 'End Date wajib diisi',
            'end_date.after'               => 'End Date harus setelah Start Date',
            'time_spent.required'          => 'Time Spent wajib diisi',
            'time_spent.min'               => 'Time Spent minimal 1 menit',
            'solution.required'            => 'Solution wajib diisi',
        ];

        if (!$isFromUser) {
            $rules['support_id'] = 'required|exists:users,id';
            $rules['priority_id'] = 'required|exists:priority,id';
            $rules['image'] = 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240';

            $statusId = $request->status_id;

            if ($statusId == 2) {
                $rules['start_date'] = 'required|date';
            }

            if ($statusId == 3) {
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after:start_date';
                $rules['time_spent'] = 'required|integer|min:1';
                $rules['solution'] = 'required|string';
            }
        } else {
            $rules['image'] = 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5120';
        }

        $validated = $request->validate($rules, $messages);

        $validated['request_date'] = now();
        $validated['waiting_hour'] = 0;
        $validated['is_late'] = isset($validated['time_spent']) && $validated['time_spent'] > 480;

        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $path = $file->store('tickets', 'public');
                $validated['image'] = $path;
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Gagal upload file: ' . $e->getMessage()])->withInput();
            }
        }

        try {
            $ticket = Ticket::create($validated);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan ticket: ' . $e->getMessage()])->withInput();
        }

        if (!$isFromUser && $request->status_id == 3 && $ticket->user && $ticket->user->email) {
            $ticket->user->notify(
                (new TicketDoneNotification($ticket))
            );
        }

        $route = $isFromUser ? 'DashboardTicketsUser.indexUser' : 'DashboardTicketsAdmin.index';
        return redirect()->route($route)
            ->with('success', '✅ Ticket berhasil ditambahkan!');
    }

    private function sendDoneNotification($ticket)
    {
        if ($ticket->user && $ticket->user->email) {
            $ticket->user->notify(
                (new TicketDoneNotification($ticket))
            );
        }
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $rules = [
            'support_id'  => 'required|exists:users,id',
            'status_id'   => 'required|exists:status,id',
            'priority_id' => 'required|exists:priority,id',
            'assets_id' => 'nullable|integer',

        ];

        $messages = [
            'support_id.required'  => 'IT Support wajib dipilih',
            'status_id.required'   => 'Status wajib dipilih',
            'priority_id.required' => 'Priority wajib dipilih',
            'start_date.required'  => 'Start Date wajib diisi',
            'end_date.required'    => 'End Date wajib diisi untuk status Done',
            'end_date.after'       => 'End Date harus setelah Start Date',
            'time_spent.required'  => 'Time Spent wajib diisi',
            'solution.required'    => 'Solution wajib diisi',
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
            $rules['solution']    = 'required|string';
        }

        if ($request->has('notes') && !empty($request->notes)) {
            $rules['notes'] = 'nullable|string|min:5';
        }

        $validated = $request->validate($rules, $messages);

        // Jangan ubah assets_id kalau tidak dikirim
        if (!$request->filled('assets_id')) {
            unset($validated['assets_id']);
        }


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
        // Validasi input
        $validated = $request->validate([
            'status_id'  => 'required|integer',
            'time_spent' => 'nullable|integer',
            'solution'   => 'required|string',
            'notes'      => 'nullable|string',
        ]);
    
        // Hitung time_spent
        $timeSpent = $validated['time_spent'] ?? $ticket->time_spent ?? 0;
        $validated['time_spent'] = $timeSpent;
    
        // Tandai apakah terlambat (lebih dari 480 menit / 8 jam)
        $validated['is_late'] = $timeSpent > 480;
    
        // Jika status Done (3), set end_date sekarang
        if ($validated['status_id'] == 3) {
            $validated['end_date'] = now();
        }
    
        // Update ticket dengan data validasi
        $ticket->update($validated);
    
        // Kirim notifikasi Done
        if ($validated['status_id'] == 3) {
            $this->sendDoneNotification($ticket);
        }
    
        return back()->with('success', 'Ticket updated successfully.');
    }

}
