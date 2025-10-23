<?php

namespace App\Http\Controllers;

use App\Models\ProjectHeader;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDetail;

class ProjectController extends Controller
{
   public function index(Request $request)
{
    $start = $request->start_date;
    $end = $request->end_date;
    $filter = ProjectHeader::betweenRequestDates($start, $end);
    $stats = ProjectHeader::statistik($start, $end);

    // 🔹 Ambil masing-masing status
    $waitingProject    = (clone $filter)->waiting()->get();
    $inProgressProject = (clone $filter)->inProgress()->get();
    $voidProject       = (clone $filter)->void()->get();
    $doneProject       = (clone $filter)->done()->get();
    $pendingProject    = (clone $filter)->pending()->get();

    // 🔹 Data pendukung
    $data = ProjectHeader::data();
    $users          = $data['users'];
    $statuses       = $data['statuses'];
    $priorities     = $data['priorities'];
    $developers     = $data['developers'];
    $generateticket = $data['generateticket'];

    // 🔹 Ambil data project yang mau di-edit (GANTI JADI $project)
    $project = null;
    $projectDetail = null;
    
    if ($request->has('id')) {
        $project = ProjectHeader::with('projectDetails')->find($request->id);
        
        if ($project) {
            // Ambil detail terakhir untuk isi developer_name dan memo
            $projectDetail = $project->projectDetails()->latest()->first();
        }
    }

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
        'priorities',
        'generateticket',
        'project',        // ✅ Ganti dari projectheader
        'projectDetail',  // ✅ Tambahkan ini
        'developers'
    ));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code'   => 'nullable|string|max:10',
            'developer_name' => 'nullable|string',
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

public function update(Request $request, ProjectHeader $project)
{
    // 🔹 Validasi data header
    $validatedHeader = $request->validate([
        'progress_percent' => 'required|numeric|min:0|max:100',
        'status_id'        => 'required|integer',
        'memo'             => 'nullable|string',
        'developer_name'   => 'nullable|string',
    ]);

    // 🔹 Ambil progress_percent & status_id dari input
    $progressPercent = $validatedHeader['progress_percent'];
    $statusId = $validatedHeader['status_id'];

    // 🔹 Hitung dulu effective_end_date (selalu dihitung)
    $endDate = \Carbon\Carbon::parse($project->end_date);
    $totalPendingMinutes = $project->total_pending_minutes ?? 0;
    $effectiveEndDate = $endDate->copy()->addMinutes($totalPendingMinutes);

    // 🔹 Siapkan data update
    $updateData = [
        'progress_percent' => $progressPercent,
        'status_id'        => $statusId,
        'progress_date'    => now(),
        'memo'             => $validatedHeader['memo'] ?? null,
        'effective_end_date' => $effectiveEndDate, // <- tetap diset biar selalu ke-update
    ];

    // 🔹 Kalau status DONE (3) dan progress 100%
    if ($statusId == 3 && $progressPercent == 100) {
        $actualEndDate = now();

        // 🔹 Tentukan apakah telat atau tidak
        $isLate = $actualEndDate->greaterThan($effectiveEndDate) ? 1 : 0;

        // 🔹 Tambahkan field tambahan
        $updateData['actual_end_date'] = $actualEndDate;
        $updateData['is_late'] = $isLate;
    }

    // 🔹 Update tabel project_header
    $project->update($updateData);

    // 🔹 Simpan juga ke project_detail
    ProjectDetail::create([
        'project_header_id' => $project->id,
        'progress_date'     => now(),
        'progress_percent'  => $progressPercent,
        'status_id'         => $statusId,
        'memo'              => $validatedHeader['memo'] ?? null,
        'developer_name'    => $validatedHeader['developer_name'] ?? null,
    ]);

    return redirect()->route('project.index')
        ->with('success', 'Progress project berhasil diperbarui dan disimpan ke detail.');
}




    public function updateStatus(Request $request, ProjectHeader $project)
    {
        $project->update([
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('project.index');
    }

    public function updateProgress(Request $request, ProjectHeader $project)
    {
        $project->update([
            'status_id' => $request->status_id,
            'actual_start_date' => now(),
        ]);
        return redirect()->route('project.index');
    }


    public function create() {}
    public function edit() {}
}
