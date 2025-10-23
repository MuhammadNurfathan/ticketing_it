<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    public function index(Request $request){
        $start = $request->start_date;
        $end   = $request->end_date;
        $stats = Ticket::getStatsFiltered($start, $end);
        $doneTicketsQuery = Ticket::betweenRequestDates($start, $end);
        $doneTickets = $doneTicketsQuery->whereHas('status', function ($q) {$q->whereIn('status_name', ['Done', 'Feedback']);})->with('feedback')->orderBy('updated_at', 'desc')->get();
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

    public function indexUser(){
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
            return $view->with('error', '⚠️ Kamu tidak bisa membuat tiket baru karena masih ada tiket DONE tanpa feedback.');
        }

        return $view;
    }

    public function create(){
        $data = Ticket::data();
        return view(
            'tickets.Create', [
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

    public function createUser(){
        $user = Auth::user();
        $hasDoneWithoutFeedback = Ticket::where('user_id', $user->id)->where('status_id', 3)->exists();

        if ($hasDoneWithoutFeedback) {
            return redirect()->route('DashboardTicketsUser.indexUser')
                ->with('error', '⚠️ Kamu tidak bisa membuat tiket baru karena masih ada tiket yang selesai (DONE) tetapi belum diberi feedback.');
        }
        $data = Ticket::data();

        return view('tickets.createUser', [
            'users'          => $data['users'],
            'assets'         => $data['assets'],
            'categories'     => $data['categories'],
            'generateticket' => $data['generateticket'],
        ]);
    }

    public function updateStatus(Request $request, Ticket $ticket){
        if ($request->status_id == 4) {
            $ticket->update([
                'status_id' => $request->status_id,
                'notes'     => $request->notes,
            ]);
        } else {
            $ticket->update([
                'status_id' => $request->status_id,
            ]);
        }

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'ticket_code'         => 'nullable',
            'user_id'             => 'nullable',
            'support_id'          => 'nullable',
            'problem_category_id' => 'nullable|exists:problem_categories,id',
            'assets_id'           => 'nullable|exists:assets,id',
            'status_id'           => 'nullable|exists:status,id',
            'priority_id'         => 'nullable|exists:priority,id',
            'problem'             => 'nullable|string',
            'solution'            => 'nullable|string',
            'notes'               => 'nullable|string',
            'request_date'        => 'nullable|date',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after:start_date',
            'time_spent'          => 'nullable|integer',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5000',]);
        $validated['request_date'] = now();
        $validated['waiting_hour'] = 0;
        $validated['is_late'] = isset($validated['time_spent']) && $validated['time_spent'] > 480 ? true : false;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public');
            $validated['image'] = $path;
        }

        Ticket::create($validated);

        if ($request->input('from') === 'user') {
            return redirect()->route('DashboardTicketsUser.indexUser')
                ->with('success', 'Ticket berhasil ditambahkan!');
        } else {
            return redirect()->route('DashboardTicketsAdmin.index')
                ->with('success', 'Ticket berhasil ditambahkan!');
        }
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

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'ticket_code'         => 'nullable',
            'user_id'             => 'nullable',
            'support_id'          => 'nullable',
            'problem_category_id' => 'nullable|exists:problem_categories,id',
            'assets_id'           => 'nullable|exists:assets,id',
            'status_id'           => 'nullable|exists:status,id',
            'priority_id'         => 'nullable|exists:priority,id',
            'problem'             => 'nullable|string',
            'solution'            => 'nullable|string',
            'notes'               => 'nullable|string',
            'request_date'        => 'nullable|date',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'time_spent'          => 'nullable|integer',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240',
        ]);

        // 🔹 Upload file baru jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public');
            $validated['image'] = $path;
        }

        // 🔹 Hitung waiting_hour jika status bukan Waiting
        if ($request->status_id != 1) {
            $validated['waiting_hour'] = $ticket->request_date->diffInMinutes(now(), true);
        }

        // 🔹 Tentukan apakah late atau tidak (time_spent > 480)
        if (isset($validated['time_spent'])) {
            $validated['is_late'] = $validated['time_spent'] > 480 ? true : false;
        } else {
            // kalau tidak diisi, pakai nilai sebelumnya
            $validated['is_late'] = $ticket->is_late;
        }

        // 🔹 Update ke database
        $ticket->update($validated);

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Ticket berhasil diupdate!');
    }

    public function updateStatusDone(Request $request, Ticket $ticket)
    {
        $statusId  = $request->input('status_id');
        $timeSpent = $request->input('time_spent');
        $solution  = $request->input('solution');
        $notes     = $request->input('notes');

        $ticket->status_id  = $statusId;
        $ticket->time_spent = $timeSpent;
        $ticket->solution   = $solution;
        $ticket->notes      = $notes;

        // jika status Done (3) dan end_date null, set sekarang
        if ($statusId == 3 && !$ticket->end_date) {
            $ticket->end_date = now();
        }

        $ticket->save();

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }
}
