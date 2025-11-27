<?php

namespace App\Http\Controllers;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(){
        $statuses = Status::latest()->get();
        return view('master/status.index', compact('statuses'));
    }

    public function create(){
        return view('master/status.create');
    }

    public function store(Request $request){
        $request->validate([
            'status_name' => 'required|string|max:255|unique:status,status_name',
        ]);

        try {
            Status::create([
                'status_name' => $request->status_name,
            ]);

            return redirect()->route('status.index')
                ->with('success', 'Status berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan status: ' . $e->getMessage());
        }
    }

    public function edit(Status $status){
        return view('master/status.edit', compact('status'));
    }

    public function update(Request $request, Status $status){
        $request->validate([
            'status_name' => 'required|string|max:255',
        ]);

        try {
            $status->update([
                'status_name' => $request->status_name,
            ]);

            return redirect()->route('status.index')
                ->with('success', 'Status berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function destroy(Status $status){
        try {
            $status->delete();
            return redirect()->route('status.index')
                ->with('success', 'Status berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('status.index')
                ->with('error', 'Gagal menghapus status: ' . $e->getMessage());
        }
    }
}
