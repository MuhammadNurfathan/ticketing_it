<?php

namespace App\Http\Controllers;

use App\Models\Pending;
use App\Models\ProjectHeader;
use App\Models\ProjectDetail;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /* =========================================
     * INDEX
     * ========================================= */
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $filter = ProjectHeader::betweenRequestDates($start, $end);
        $stats  = ProjectHeader::statistik($start, $end);

        $waitingProject    = (clone $filter)->waiting()->get();
        $inProgressProject = (clone $filter)->inProgress()->get();
        $voidProject       = (clone $filter)->void()->get();
        $doneProject       = (clone $filter)->done()->get();
        $pendingProject    = (clone $filter)->pending()->get();

        $data = ProjectHeader::data();
        $users          = $data['users'];
        $statuses       = $data['statuses'];     // pastiin ini cuma context project
        $priorities     = $data['priorities'];
        $developers     = $data['developers'];
        $generateticket = $data['generateticket'];

        $project  = null;
        $pendings = collect();
        $details  = collect();

        if ($request->has('id')) {
            $project = ProjectHeader::with(['details.status', 'details.developer', 'pendings'])->find($request->id);

            if ($project) {
                $pendings = Pending::where('project_header_id', $project->id)->orderBy('created_at', 'ASC')->get();

                $details = ProjectDetail::with(['status', 'developer'])
                    ->where('project_header_id', $project->id)
                    ->orderBy('progress_date', 'ASC')
                    ->get();
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
            'developers',
            'details',
            'pendings'
        ));
    }

    /* =========================================
     * STORE (CREATE PROJECT)
     * ========================================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code' => 'nullable|string|max:10',
            'project_name' => 'required|string|max:255',
            'requestor_id' => 'required|exists:users,id',
            'priority_id'  => 'required|exists:priorities,id',
            'description'  => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        $waitingStatusId = Status::where('context', 'project')->where('type', 'waiting')->value('id');

        ProjectHeader::create([
            ...$validated,
            'request_date' => now(),
            'status_id'    => $waitingStatusId,
            'progress_percent' => 0,
        ]);

        return redirect()->route('project.index')->with('success', 'Project berhasil ditambahkan.');
    }

    /* =========================================
     * UPDATE PROGRESS (ALWAYS IN_PROGRESS)
     * =========================================
     * - Dari form update biasa, jangan bisa done disini.
     * - Status selalu dipaksa in_progress.
     * - progress_date wajib.
     * - log detail pakai developer_id.
     * ========================================= */
    public function update(Request $request, ProjectHeader $project)
    {
        $validated = $request->validate([
            'progress_percent' => 'required|integer|min:0|max:99', // ✅ integer & max 99
            'description'      => 'nullable|string',
            'developer_id'     => 'nullable|exists:users,id',
            'progress_date'    => 'required|date',
        ]);

        $inProgressId = Status::where('context', 'project')->where('type', 'in_progress')->value('id');
        if (!$inProgressId) {
            return back()->with('error', 'Status In Progress belum ada di table statuses.');
        }

        $totalPendingMinutes = (int) $project->pendings()
            ->whereNotNull('duration_minutes')
            ->sum('duration_minutes');

        $endDate = $project->end_date ? Carbon::parse($project->end_date) : null;
        $effectiveEndDate = $endDate ? $endDate->copy()->addMinutes($totalPendingMinutes) : null;

        $project->update([
            'progress_percent'      => (int) $validated['progress_percent'],
            'status_id'             => (int) $inProgressId,
            'progress_date'         => $validated['progress_date'],
            'description'           => $validated['description'] ?? $project->description,
            'total_pending_minutes' => $totalPendingMinutes,
            'effective_end_date'    => $effectiveEndDate,
        ]);

        ProjectDetail::create([
            'project_header_id' => $project->id,
            'progress_date'     => $validated['progress_date'],
            'progress_percent'  => (int) $validated['progress_percent'],
            'status_id'         => (int) $inProgressId,
            'description'       => $validated['description'] ?? null,
            'developer_id'      => $validated['developer_id'] ?? null,
        ]);

        return redirect()->route('project.index')->with('success', 'Progress berhasil diupdate (auto In Progress).');
    }

    /* =========================================
     * DONE (SEPARATE ACTION)
     * =========================================
     * - Tombol done khusus
     * - otomatis status done + progress 100
     * - set actual_end_date + is_late + total_pending_minutes
     * - log detail
     * ========================================= */
    public function done(Request $request, ProjectHeader $project)
    {
        $validated = $request->validate([
            'description'             => 'nullable|string',
            'developer_id'            => 'nullable|exists:users,id',  // ✅ nullable
            'apply_pending_duration'  => 'required|in:0,1',
        ]);

        $actorId = (int)($validated['developer_id'] ?? auth()->id()); // ✅ fallback user login

        $doneStatusId = Status::where('context', 'project')->where('type', 'done')->value('id');
        if (!$doneStatusId) return back()->with('error', 'Status Done belum ada di table statuses.');

        $apply = (int)$validated['apply_pending_duration'] === 1;
        $appliedPendingMinutes = $apply ? $this->appliedPendingMinutes($project) : 0;

        $endDate = $project->end_date ? Carbon::parse($project->end_date) : null;
        $effectiveEndDate = $endDate ? $endDate->copy()->addMinutes($appliedPendingMinutes) : null;

        $actualEndDate = now();
        $isLate = ($effectiveEndDate && $actualEndDate->greaterThan($effectiveEndDate)) ? 1 : 0;

        $progressDate = now();

        $project->update([
            'progress_percent'      => 100,
            'status_id'             => (int)$doneStatusId,
            'progress_date'         => $progressDate,
            'description'           => $validated['description'] ?? $project->description,
            'total_pending_minutes' => (int)$appliedPendingMinutes,
            'effective_end_date'    => $effectiveEndDate,
            'actual_end_date'       => $actualEndDate,
            'is_late'               => $isLate,
        ]);

        ProjectDetail::create([
            'project_header_id' => $project->id,
            'progress_date'     => $progressDate,
            'progress_percent'  => 100,
            'status_id'         => (int)$doneStatusId,
            'description'       => 'DONE. AppliedPending=' . ($apply ? 'YES' : 'NO') . '. ' . ($validated['description'] ?? ''),
            'developer_id'      => $actorId,
        ]);

        return redirect()->route('project.index')->with('success', 'Project berhasil di-Done-kan (100%).');
    }

    /* =========================================
     * START PROJECT (PILIH dari WAITING -> IN_PROGRESS)
     * ========================================= */
    public function updateProgress(Request $request, ProjectHeader $project)
    {
        $inProgressId = Status::where('context', 'project')->where('type', 'in_progress')->value('id');

        $project->update([
            'status_id'         => $inProgressId,
            'actual_start_date' => now(),
        ]);

        return redirect()->route('project.index');
    }

    /* =========================================
     * PENDING
     * ========================================= */
    public function storePending(Request $request, $projectHeaderId)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'developer_id' => 'required|exists:users,id', // siapa yang pending-in
            'count_to_effective' => 'required|in:0,1',    // YES/NO apply duration
        ]);

        $project = ProjectHeader::findOrFail($projectHeaderId);

        $pendingStatusId = Status::where('context', 'project')->where('type', 'pending')->value('id');
        if (!$pendingStatusId) return back()->with('error', 'Status Pending belum ada.');

        // buat record pending
        Pending::create([
            'project_header_id' => $projectHeaderId,
            'reason'            => $validated['reason'],
            'pending_start'     => now(),
            'count_to_effective' => (bool) $validated['count_to_effective'],
            // duration_minutes nanti diisi saat continue (pending_end)
        ]);

        // update header status
        $project->update([
            'status_id' => $pendingStatusId,
        ]);

        // LOG ke details (siapa yg pending-in)
        ProjectDetail::create([
            'project_header_id' => $project->id,
            'progress_date'     => now(),
            'progress_percent'  => (int) ($project->progress_percent ?? 0),
            'status_id'         => (int) $pendingStatusId,
            'description'       => 'PENDING: ' . $validated['reason'],
            'developer_id'      => (int) $validated['developer_id'],
        ]);

        return back()->with('success', 'Pending disimpan, status & log detail masuk.');
    }
    /* =========================================
     * CONTINUE from PENDING -> IN_PROGRESS
     * ========================================= */
    public function continueProgress(Request $request, $projectHeaderId)
    {
        $validated = $request->validate([
            'developer_id' => 'required|exists:users,id',     // siapa yg continue
            'use_override' => 'required|in:0,1',              // pakai durasi manual?
            'duration_override' => 'nullable|integer|min:0',  // kalau manual, menitnya
        ]);

        $project = ProjectHeader::findOrFail($projectHeaderId);

        $pending = $project->pendings()->whereNull('pending_end')->latest()->first();
        if (!$pending) {
            return back()->with('error', 'Tidak ada pending aktif.');
        }

        // tutup pending
        $pending->pending_end = now();

        $autoMinutes = now()->diffInMinutes($pending->pending_start);

        $useOverride = (int) $validated['use_override'] === 1;
        $overrideMinutes = $useOverride ? (int) ($validated['duration_override'] ?? 0) : null;

        $finalMinutes = $useOverride ? $overrideMinutes : $autoMinutes;

        $pending->duration_minutes = abs($finalMinutes);
        $pending->duration_override = $useOverride ? $overrideMinutes : null;
        $pending->save();

        $inProgressId = Status::where('context', 'project')->where('type', 'in_progress')->value('id');
        if (!$inProgressId) return back()->with('error', 'Status In Progress belum ada.');

        // hitung pending minutes yang APPLIED (berdasarkan count_to_effective)
        $appliedPendingMinutes = $this->appliedPendingMinutes($project);

        $endDate = $project->end_date ? Carbon::parse($project->end_date) : null;
        $effectiveEndDate = $endDate ? $endDate->copy()->addMinutes($appliedPendingMinutes) : null;

        // update header balik in_progress + simpan total pending yang diterapkan
        $project->update([
            'status_id'             => (int) $inProgressId,
            'total_pending_minutes' => (int) $appliedPendingMinutes,
            'effective_end_date'    => $effectiveEndDate,
        ]);

        // LOG ke details
        ProjectDetail::create([
            'project_header_id' => $project->id,
            'progress_date'     => now(),
            'progress_percent'  => (int) ($project->progress_percent ?? 0),
            'status_id'         => (int) $inProgressId,
            'description'       => 'CONTINUE dari Pending. Durasi=' . $pending->duration_minutes .
                ' menit. Applied=' . ($pending->count_to_effective ? 'YES' : 'NO'),
            'developer_id'      => (int) $validated['developer_id'],
        ]);

        return back()->with('success', 'Project dilanjutkan, pending selesai & log masuk.');
    }

    /* =========================================
     * UPDATE STATUS (VOID, dll)
     * ========================================= */
    public function updateStatus(Request $request, ProjectHeader $project)
    {
        $validated = $request->validate([
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where(fn($q) => $q->where('context', 'project')),
            ],
            'notes' => 'nullable|string',
        ]);

        $data = ['status_id' => (int) $validated['status_id']];

        $voidId = Status::where('context', 'project')->where('type', 'void')->value('id');
        if ($voidId && (int) $validated['status_id'] === (int) $voidId) {
            $request->validate(['notes' => 'required|string']);
            $data['notes'] = $validated['notes'];
        }

        $project->update($data);

        return redirect()->route('project.index')->with('success', 'Status project berhasil diupdate!');
    }

    /* =========================================
     * HISTORY
     * ========================================= */
    public function history(ProjectHeader $project)
    {
        $pendings = Pending::where('project_header_id', $project->id)->orderBy('created_at', 'ASC')->get();

        $details = ProjectDetail::with(['status', 'developer'])
            ->where('project_header_id', $project->id)
            ->orderBy('progress_date', 'ASC')
            ->get();

        return view('project.history', compact('pendings', 'details', 'project'));
    }

    private function appliedPendingMinutes(ProjectHeader $project): int
    {
        return (int) $project->pendings()
            ->whereNotNull('duration_minutes')
            ->where('count_to_effective', true)
            ->sum('duration_minutes');
    }
}
