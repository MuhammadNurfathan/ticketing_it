<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{

    public function index(Request $request)
    {
        // ==================== AMBIL DATA FILTER ====================
        // Ambil input dari form GET
        $start = $request->start_date;         // tanggal mulai filter
        $end   = $request->end_date;           // tanggal akhir filter
        $filterType = $request->filter_type ?? 'request';  
        // jika filter_type tidak ada, default = 'request' (filter by tanggal request)

        // ==================== STATISTIK ====================
        if ($filterType === 'end') {
            // Jika filter berdasarkan tanggal selesai tiket (DONE)
            // Hitung statistik tiket dalam rentang tanggal selesai
            $stats = Ticket::getStatsByEndDate($start, $end);

            // Ambil query tiket berdasarkan tanggal selesai
            // (Belum dijalankan, hanya query builder)
            $ticketsQuery = Ticket::betweenEndDates($start, $end);
        } else {
            // Jika filter berdasarkan tanggal request tiket (default)
            // Hitung statistik tiket dalam rentang tanggal request
            $stats = Ticket::getStatsByRequest($start, $end);

            // Ambil query tiket berdasarkan tanggal request
            $ticketsQuery = Ticket::betweenRequestDates($start, $end);
        }

        // ==================== DETAIL PER STATUS ====================
        // Clone query supaya query utama tidak terganggu saat filter per status
        // kemudian ambil data actual dari database
        $waitingTickets    = (clone $ticketsQuery)->waiting()->get();        // status Waiting
        $pendingTickets    = (clone $ticketsQuery)->pending()->get();        // status Pending
        $inProgressTickets = (clone $ticketsQuery)->inProgress()->get();     // status In Progress
        $doneTickets       = (clone $ticketsQuery)->done()->get();           // status Done
        $voidTickets       = (clone $ticketsQuery)->void()->get();           // status Void

        // ==================== RETURN VIEW ====================
        // Kirim data ke view DashboardTicket
        // compact() otomatis membuat array ['nama_var' => $nama_var]
        return view('DashboardTicketsAdmin', compact(
            'stats',            // statistik umum
            'waitingTickets',   // tiket Waiting
            'pendingTickets',   // tiket Pending
            'inProgressTickets',// tiket In Progress
            'doneTickets',      // tiket Done
            'voidTickets',      // tiket Void
            'start',            // tanggal mulai filter (untuk form)
            'end',              // tanggal akhir filter (untuk form)
            'filterType'        // tipe filter (request atau end)
        ));
    }

    public function create(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }
    
public function updateStatus(Request $request, Ticket $ticket)
{
    $ticket->update([
        'status_id' => $request->status_id, // ambil dari input form
    ]);

    return redirect()->route('DashboardTicketsAdmin.index');
}

}
