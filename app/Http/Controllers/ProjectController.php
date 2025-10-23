<?php

namespace App\Http\Controllers;

use App\Models\ProjectHeader;
use Illuminate\Http\Request;
use App\Models\Project;


class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $filter = ProjectHeader::betweenRequestDates($start, $end);
        $stats = ProjectHeader::statistik($start, $end);

        // 🔹 Ambil masing-masing status dari query dasar
        $waitingProject    = (clone $filter)->waiting()->get();
        $inProgressProject = (clone $filter)->inProgress()->get();
        $voidProject       = (clone $filter)->void()->get();
        $doneProject       = (clone $filter)->done()->get();
        $pendingProject      = (clone $filter)->pending()->get();
        $data = ProjectHeader::data();
        // Dari data array
        $users          = $data['users'];
        $statuses       = $data['statuses'];
        $developers     = $data['developers'];
        $priorities     = $data['priorities'];
        $generateticket = $data['generateticket'];


        return view('project.header.index', compact(
            'stats',
            'waitingProject',
            'pendingProject',
            'inProgressProject',
            'voidProject',
            'doneProject',
            'start',
            'end',
            'users',
            'statuses',
            'developers',
            'priorities',
            'generateticket'
        ));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code'   => 'nullable|string|max:10',
            'project_name'   => 'nullable|string|max:255',
            'requestor_id'   => 'required|exists:users,id',
            'priority_id'    => 'required|exists:priority,id',
            'status_id'      => 'required|exists:status,id',
            'description'    => 'nullable|string',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'notes'          => 'nullable|string',
        ]);
        $validated['request_date'] = now();

        ProjectHeader::create($validated);

        return redirect()->route('project.index')->with('success', 'Project berhasil ditambahkan.');
    }


    public function edit() {}

    public function update() {}

    public function updateStatus(Request $request, ProjectHeader $project)
    {
        $project->update([
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('project.index');
    }

    public function updateProgress(Request $request, ProjectHeader $project)
    {
        $validated = $request->validate([
            'status_id' => 'required',
            'actual_start_date' => 'nullable|date'
        ]);

        $project->update([
            'status_id' => $request->status_id,
            'actual_start_date' => now(),
        ]);
        return redirect()->route('project.index');
    }
}
