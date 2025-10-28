<?php

namespace App\Http\Controllers;

use App\Models\Pending;
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

        // Filter utama
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

        // 🔹 Jika ada project ID yang dikirim (misalnya untuk modal/detail)
        $project = null;
        $projectDetail = null;
        $pendings = collect();
        $details = collect();

      if ($request->has('id')) {
    $project = ProjectHeader::with('projectDetails')->find($request->id);

    if ($project) {
        $pendings = Pending::where('id_project_header', $project->id)->orderBy('created_at', 'ASC')->get();

        $details  = ProjectDetail::where('project_header_id', $project->id)->orderBy('created_at', 'ASC')->get();
    }
}


        return view('project.index', compact(
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
            'project',
            'projectDetail',
            'developers',
            'details',
            'pendings'
        ));
    }

    public function storePending(Request $request, $projectHeaderId)
    {
        // 🔹 Validasi input reason aja
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // 🔹 Simpan data pending baru
        Pending::create([
            'id_project_header' => $projectHeaderId, // harus sama persis dengan fillable
            'reason'            => $request->reason,
            'pending_start'     => now(),
        ]);

        $project = ProjectHeader::findOrFail($projectHeaderId);
        $project->status_id = 6;
        $project->save();


        return back()->with('success', 'Alasan pending berhasil disimpan dan status project diupdate.');
    }

    public function continueProgress(Request $request, $projectHeaderId)
    {
        // 🔹 Ambil project
        $project = ProjectHeader::findOrFail($projectHeaderId);

        // 🔹 Ambil pending terakhir yang belum selesai (pending_end null)
        $pending = $project->pendings()->whereNull('pending_end')->latest()->first();

        if ($pending) {
            $pending->pending_end = now();

            // 🔹 Hitung durasi baru dan tambahkan ke duration_minutes lama
            $newDuration = now()->diffInMinutes($pending->pending_start);
            $pending->duration_minutes = ($pending->duration_minutes ?? 0) + abs($newDuration);

            $pending->save();
        }


        // 🔹 Update status project (misal ke In Progress = 2)
        $project->update([
            'status_id' => $request->status_id ?? 2
        ]);

        return back()->with('success', 'Project dilanjutkan, pending selesai dan durasi tercatat.');
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

        // 🔹 Hitung total pending minutes dari tabel pending terkait project
        // pastikan selalu integer dan default 0 kalau kosong
        $totalPendingMinutes = (int) $project->pendings()->whereNotNull('duration_minutes')->sum('duration_minutes');


        // 🔹 Hitung dulu effective_end_date (selalu dihitung)
        $endDate = \Carbon\Carbon::parse($project->end_date);
        $effectiveEndDate = $endDate->copy()->addMinutes($totalPendingMinutes);

        // 🔹 Siapkan data update
        $updateData = [
            'progress_percent'   => $progressPercent,
            'status_id'          => $statusId,
            'progress_date'      => now(),
            'memo'               => $validatedHeader['memo'] ?? null,
            'effective_end_date' => $effectiveEndDate,

        ];

        if ($statusId == 3 && $progressPercent == 100) {
            $actualEndDate = now();

            // 🔹 Tentukan apakah telat atau tidak
            $isLate = $actualEndDate->greaterThan($effectiveEndDate) ? 1 : 0;

            // 🔹 Tambahkan field tambahan
            $updateData['actual_end_date'] = $actualEndDate;
            $updateData['is_late'] = $isLate;

            // 🔹 Update total_pending_minutes juga hanya di sini
            $updateData['total_pending_minutes'] = $totalPendingMinutes;
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
        // Data dasar yang selalu diupdate
        $data = [
            'status_id' => $request->status_id,
        ];

        // Kalau status_id = 4 (void), tambahkan notes juga
        if ($request->status_id == 4) {
            $data['notes'] = $request->notes;
        }

        $project->update($data);

        return redirect()->route('project.index')->with('success', 'Status project berhasil diupdate!');
    }

    public function updateProgress(Request $request, ProjectHeader $project)
    {
        $project->update([
            'status_id' => $request->status_id,
            'actual_start_date' => now(),
        ]);
        return redirect()->route('project.index');
    }

    public function history(ProjectHeader $project)
    {
        $pendings = Pending::where('id_project_header', $project->id)->orderBy('created_at', 'ASC')->get();

        $details = ProjectDetail::where('project_header_id', $project->id)
        ->orderBy('progress_date', 'ASC') // urut dari paling lama ke terbaru
        ->get();
        
        return view('project.history', compact('pendings', 'details','project'));
    }
}
