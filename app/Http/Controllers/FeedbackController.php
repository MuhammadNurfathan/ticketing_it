<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        // Ambil feedback + relasi ticket (biar ga N+1)
        $feedback = Feedback::with(['ticket.support']) // pastikan relasi ticket & support ada
            ->latest()
            ->get();

        // Average per kategori
        $avgSpeed    = number_format((float) Feedback::avg('speed_rating'), 1);
        $avgWaiting  = number_format((float) Feedback::avg('waiting_rating'), 1);
        $avgSolution = number_format((float) Feedback::avg('solution_rating'), 1);

        // Average overall (rata-rata dari 3 avg)
        $avgOverall  = number_format(((float)$avgSpeed + (float)$avgWaiting + (float)$avgSolution) / 3, 1);

        return view('feedback.index', compact(
            'feedback',
            'avgSpeed',
            'avgWaiting',
            'avgSolution',
            'avgOverall'
        ));
    }

    public function form($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        // kalau sudah pernah feedback, edit data yg lama
        $feedback = Feedback::where('ticket_id', $ticket->id)->first();

        return view('feedback.create', compact('ticket', 'feedback'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'ticket_id'        => 'required|exists:tickets,id',
            'speed_rating'     => 'required|integer|min:1|max:5',
            'waiting_rating'   => 'required|integer|min:1|max:5',
            'solution_rating'  => 'required|integer|min:1|max:5',
            'comment'          => 'required|string',
        ]);

        // ✅ update kalau sudah ada, create kalau belum
        Feedback::updateOrCreate(
            ['ticket_id' => $data['ticket_id']],
            [
                'speed_rating'    => $data['speed_rating'],
                'waiting_rating'  => $data['waiting_rating'],
                'solution_rating' => $data['solution_rating'],
                'comment'         => $data['comment'],
            ]
        );

        // Update status tiket jadi 5 (sesuai code kamu)
        Ticket::where('id', $data['ticket_id'])->update(['status_id' => 5]);

        // route kamu tadi agak beda-beda, sesuaikan salah satu yang bener
        return redirect()->route('DashboardTicketsUser.index')
            ->with('success', 'Feedback berhasil disimpan dan status tiket diperbarui!');
    }
}