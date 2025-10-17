<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{

    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $filterType = $request->filter_type ?? 'request';

        $stats = Ticket::getStatsFiltered($start, $end, $filterType);

        // Filter berdasarkan tanggal
        if ($filterType === 'end') {
            $doneTicketsQuery = Ticket::betweenEndDates($start, $end);
        } else {
            $doneTicketsQuery = Ticket::betweenRequestDates($start, $end);
        }

        // Ambil tiket yang status-nya "Done" atau "Done Feedback"
        $doneTickets = $doneTicketsQuery
            ->whereHas('status', function ($q) {
                $q->whereIn('status_name', ['Done', 'Done Feedback']);
            })
            ->with('feedback') // ← ini tambahan
            ->get();

        // Ambil kategori status lain
        $waitingTickets    = Ticket::waiting()->get();
        $inProgressTickets = Ticket::inProgress()->get();
        $voidTickets       = Ticket::void()->get();

        return view('tickets/DashboardTicketsAdmin', compact(
            'stats',
            'waitingTickets',
            'inProgressTickets',
            'doneTickets',
            'voidTickets',
            'start',
            'end',
            'filterType'
        ));
    }


 public function indexUser()
{
    $userId = Auth::id();

    // Ambil tiket user, terbaru di atas
    $myTicket = Ticket::where('user_id', $userId)
        ->orderBy('created_at', 'desc') // urutkan dari terbaru
        ->get();

    // Cek apakah ada ticket dengan status Done
    $hasDoneTicket = Ticket::where('user_id', $userId)
        ->where('status_id', 3)
        ->exists();

    return view('tickets.DashboardTicketsUser', compact('myTicket', 'hasDoneTicket'));
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
    $user = Auth::user(); // ambil user login

    // 🔍 Cek apakah user punya tiket dengan status DONE (belum kasih feedback)
    $hasDoneWithoutFeedback = Ticket::where('user_id', $user->id)
        ->where('status_id', 3) // 3 = Done (belum feedback)
        ->exists();

    // 🚫 Kalau masih ada tiket Done tanpa feedback, tolak akses
    if ($hasDoneWithoutFeedback) {
        return redirect()->route('DashboardTicketsUser.indexUser')
            ->with('error', '⚠️ Kamu tidak bisa membuat tiket baru karena masih ada tiket yang selesai (DONE) tetapi belum diberi feedback.');
    }

    // ✅ Kalau aman, ambil data dan lanjut ke halaman create
    $data = Ticket::data();

    return view('tickets.CreateUser', [
        'users'          => $data['users'],
        'assets'         => $data['assets'],
        'categories'     => $data['categories'],
        'priorities'     => $data['priorities'],
        'generateticket' => $data['generateticket'],
    ]);
}


    public function updateStatus(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('DashboardTicketsAdmin.index');
    }

    public function store(Request $request)
    {
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
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'time_spent'          => 'nullable|integer',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB
        ]);

        // 🔹 Set request_date sekarang
        $validated['request_date'] = now();

        // 🔹 Upload file jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public');
            $validated['image'] = $path;
        }

        // 🔹 Hitung waiting_hour otomatis jika status bukan 'waiting' (misal id 1 = waiting)
        if (!empty($validated['status_id']) && $validated['status_id'] != 1) {
            $validated['waiting_hour'] = now()->diffInMinutes($validated['request_date']);
        }

        // 🔹 Simpan ke database
        Ticket::create($validated);

        // 🔹 Redirect berdasarkan asal
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
        $data = Ticket::data(); // ambil data tambahan seperti users, categories, etc

        return view('tickets.Edit', [ // bisa bikin view baru tickets.edit
            'ticket'      => $ticket,
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

        // 🔹 Upload file baru jika ada
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('tickets', 'public');
            $validated['image'] = $path;
        }

        // 🔹 Hitung waiting_hour otomatis
        // misal id 1 = waiting
        if (!empty($validated['status_id']) && $validated['status_id'] != 1 && !$ticket->waiting_hour) {
            $validated['waiting_hour'] = now()->diffInMinutes($ticket->request_date);
        }

        $ticket->update($validated);

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Ticket berhasil diupdate!');
    }


    public function updateStatusDone(Request $request, Ticket $ticket)
    {
        $statusId  = $request->input('status_id');
        $timeSpent = $request->input('time_spent');
        $solution  = $request->input('solution'); // ambil input solution
        $notes     = $request->input('notes');    // ambil input notes

        $ticket->status_id  = $statusId;
        $ticket->time_spent = $timeSpent;
        $ticket->solution   = $solution; // simpan solution
        $ticket->notes      = $notes;    // simpan notes

        // jika status Done (3) dan end_date null, set sekarang
        if ($statusId == 3 && !$ticket->end_date) {
            $ticket->end_date = now();
        }

        $ticket->save();

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }
}
