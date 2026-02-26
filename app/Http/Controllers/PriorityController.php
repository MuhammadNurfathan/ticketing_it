<?php

namespace App\Http\Controllers;

use App\Http\Requests\Priority\PriorityStoreRequest;
use App\Http\Requests\Priority\PriorityUpdateRequest;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index()
    {
        $priorities = Priority::latest()->get();
        return view('master/priority.index', compact('priorities'));
    }

    public function create()
    {
        return view('master/priority.create');
    }

    public function store(PriorityStoreRequest $request)
    {
        $data = $request->validated();
        Priority::create($data);

        return redirect()
            ->route('priority.index')
            ->with('success', 'Priority berhasil ditambahkan.');
    }

    public function edit(Priority $priority)
    {
        return view('master/priority.edit', compact('priority'));
    }

    public function update(PriorityUpdateRequest $request, Priority $priority)
    {
        $data = $request->validated();
        $priority->update($data);

        return redirect()
            ->route('priority.index')
            ->with('success', 'Priority berhasil diperbarui.');
    }

    public function destroy(Priority $priority)
    {
        try {
            $priority->delete();

            return redirect()
                ->route('priority.index')
                ->with('success', 'Priority berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('priority.index')
                ->with('error', 'Priority tidak dapat dihapus karena masih digunakan!');
        }
    }
}