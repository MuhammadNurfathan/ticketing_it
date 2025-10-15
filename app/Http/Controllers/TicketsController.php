<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{

   public function index(Request $request)
{
    $start = $request->start_date;
    $end   = $request->end_date;
    $filterType = $request->filter_type ?? 'request';

    $stats = Ticket::getStatsFiltered($start, $end, $filterType);

    if ($filterType === 'end') {

        $doneTicketsQuery = Ticket::done()->betweenEndDates($start, $end);
    } else {
        $doneTicketsQuery = Ticket::done()->betweenRequestDates($start, $end);
    }

    $otherTicketsQuery = Ticket::whereHas('status', function ($q) {
        $q->where('status_name', '!=', 'Done');
    });

    $ticketsQuery = $otherTicketsQuery->union($doneTicketsQuery);

    $waitingTickets    = Ticket::waiting()->get();
    $pendingTickets    = Ticket::pending()->get();
    $inProgressTickets = Ticket::inProgress()->get();
    $voidTickets       = Ticket::void()->get();
    $doneTickets       = (clone $ticketsQuery)->done()->get();

    return view('tickets/DashboardTicketsAdmin', compact(
        'stats',
        'waitingTickets',
        'pendingTickets',
        'inProgressTickets',
        'doneTickets',
        'voidTickets',
        'start',
        'end',
        'filterType'
    ));
}


    public function create()
    {
        $data = Ticket::data();
        return view('tickets.create',
            [
                'locations'  => $data['locations'],
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

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'status_id' => $request->status_id, // ambil dari input form
        ]);

        return redirect()->route('DashboardTicketsAdmin.index');
    }

    public function store(Request $request) {
         // 🔹 Validasi input
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
            'waiting_hour'        => 'nullable|integer',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'time_spent'          => 'nullable|integer',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB
        ]);

         $validated['request_date'] = now();
        // 🔹 Upload file (jika ada)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public'); // simpan ke storage/app/public/tickets
            $validated['image'] = $path;
        }

        // 🔹 Simpan ke database
        Ticket::create($validated);

        // 🔹 Redirect balik dengan pesan sukses
        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Ticket berhasil ditambahkan!');
    
    }
   public function edit($id) {
    $ticket = Ticket::with(['user', 'support', 'problemCategory', 'assets', 'priority', 'status'])->findOrFail($id);
    $data = Ticket::data(); // ambil data tambahan seperti users, categories, etc

    return view('tickets.edit', [ // bisa bikin view baru tickets.edit
        'ticket'      => $ticket,
        'locations'   => $data['locations'],
        'users'       => $data['users'],
        'assets'      => $data['assets'],
        'statuses'    => $data['statuses'],
        'categories'  => $data['categories'],
        'developers'  => $data['developers'],
        'priorities'  => $data['priorities'],
        'generateticket' => $data['generateticket'], // opsional, untuk ticket code default
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
        'waiting_hour'        => 'nullable|integer',
        'start_date'          => 'nullable|date',
        'end_date'            => 'nullable|date',
        'time_spent'          => 'nullable|integer',
        'image'               => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB
    ]);

    // 🔹 Jika ada file baru, simpan dan update path, tapi **tidak hapus file lama**
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $path = $file->store('tickets', 'public');
        $validated['image'] = $path;
    }

    $ticket->update($validated);

    return redirect()->route('DashboardTicketsAdmin.index')
        ->with('success', 'Ticket berhasil diupdate!');
}



    public function updateStatusDone(Request $request, Ticket $ticket)
{
    $statusId = $request->input('status_id');
    $timeSpent = $request->input('time_spent');

    $ticket->status_id = $statusId;
    $ticket->time_spent = $timeSpent;

    // jika status Done (3) dan end_date null, set sekarang
    if ($statusId == 3 && !$ticket->end_date) {
        $ticket->end_date = now();
    }

    $ticket->save();

    return redirect()->back()->with('success', 'Ticket updated successfully.');
}

}
