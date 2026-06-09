<?php

namespace App\Http\Controllers;

use App\Http\Requests\Status\StatusStoreRequest;
use App\Http\Requests\Status\StatusUpdateRequest;
use App\Models\Status;
 use Illuminate\Database\QueryException;
class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::latest()->get();
        return view('master/status.index', compact('statuses'));
    }

    public function create()
    {
        return view('master/status.create');
    }

    public function store(StatusStoreRequest $request)
    {
        $data = $request->validated();
        Status::create($data);

        return redirect()
            ->route('status.index')
            ->with('success', 'Status berhasil ditambahkan.');
    }

    public function edit(Status $status)
    {
        return view('master/status.edit', compact('status'));
    }

    public function update(StatusUpdateRequest $request, Status $status)
    {
        $data = $request->validated();
        $status->update($data);

        return redirect()
            ->route('status.index')
            ->with('success', 'Status berhasil diperbarui.');
    }



public function destroy(Status $status)
{
    try {
        $status->delete();

        return redirect()
            ->route('status.index')
            ->with('success', 'Status berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('status.index')
            ->with('error', 'Status tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Throwable $e) {

        return redirect()
            ->route('status.index')
            ->with('error', 'Terjadi kesalahan saat menghapus status.');

    }
}
}