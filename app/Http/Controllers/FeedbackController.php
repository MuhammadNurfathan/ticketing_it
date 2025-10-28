<?php

namespace App\Http\Controllers;

use App\Models\feedback;
use App\Models\Ticket;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $Rate =number_format(feedback::avg('rating'),1) ;
        $feedback = feedback::latest()->paginate(10);
        return view('feedback.index', compact('feedback','Rate'));
    }
    public function form($ticket_id)
    {
        // Ambil tiket
        $ticket = Ticket::findOrFail($ticket_id);

        // Ambil feedback (null kalau belum ada)
        $feedback = Feedback::where('ticket_id', $ticket->id)->first();

        return view('feedback.create', compact('ticket', 'feedback'));
    }

    // Simpan feedback (create atau update)
    public function save(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'description' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Buat feedback baru
        Feedback::create([
            'ticket_id' => $data['ticket_id'],
            'description' => $data['description'],
            'rating' => $data['rating'],
        ]);

        // Update status tiket menjadi 5
        $ticket = Ticket::findOrFail($data['ticket_id']);
        $ticket->update(['status_id' => 5]);

        return redirect()->route('DashboardTicketsUser.indexUser')
            ->with('success', 'Feedback berhasil disimpan dan status tiket diperbarui!');
    }
}
