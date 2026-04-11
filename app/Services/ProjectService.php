<?php

namespace App\Services;

use App\Models\ProjectHeader;
use App\Models\ProjectDetail;
use App\Models\Pending;
use App\Models\Status;
use Carbon\Carbon;

class ProjectService
{
    public function getIndexData($request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $filter = ProjectHeader::betweenRequestDates($start, $end);

        $projects = [
            'waitingProject'    => (clone $filter)->waiting()->get(),
            'inProgressProject' => (clone $filter)->inProgress()->get(),
            'voidProject'       => (clone $filter)->void()->get(),
            'doneProject'       => (clone $filter)->done()->get(),
            'pendingProject'    => (clone $filter)->pending()->get(),
        ];

        $project  = null;
        $details  = collect();
        $pendings = collect();

        if ($request->filled('id')) {
            $project = ProjectHeader::with(['details.status','details.developer','pendings'])
                ->find($request->id);

            if ($project) {
                $details  = $project->details()->with(['status','developer'])->get();
                $pendings = $project->pendings()->get();
            }
        }

        return array_merge($projects, ProjectHeader::data(), [
            'project' => $project,
            'details' => $details,
            'pendings'=> $pendings,
            'stats'   => ProjectHeader::statistik($start, $end),
            'start'   => $start,
            'end'     => $end,
        ]);
    }

    public function store($data)
    {
        ProjectHeader::createProject($data);
    }

    public function updateProgress($project, $data)
    {
        $statusId = Status::getId('in_progress');

        if (!$statusId) throw new \Exception('Status in_progress tidak ada');

        $pendingMinutes = $project->getTotalPendingMinutes();

        $project->updateProgressData($data, $statusId, $pendingMinutes);

        ProjectDetail::logProgress($project, $data, $statusId);
    }

    public function done($project, $data)
    {
        $statusId = Status::getId('done');

        if (!$statusId) throw new \Exception('Status done tidak ada');

        $apply = (int)$data['apply_pending_duration'] === 1;
        $pendingMinutes = $apply ? $project->getAppliedPendingMinutes() : 0;

        $effectiveEnd = $project->calculateEffectiveEnd($pendingMinutes);
        $actualEnd = now();

        $project->update([
            'progress_percent' => 100,
            'status_id' => $statusId,
            'progress_date' => now(),
            'description' => $data['description'] ?? $project->description,
            'total_pending_minutes' => $pendingMinutes,
            'effective_end_date' => $effectiveEnd,
            'actual_end_date' => $actualEnd,
            'is_late' => $effectiveEnd && $actualEnd->gt($effectiveEnd),
        ]);

        ProjectDetail::logDone($project, $data, $statusId, $apply);
    }

    public function startProject($project)
    {
        $statusId = Status::getId('in_progress');

        $project->update([
            'status_id' => $statusId,
            'actual_start_date' => now(),
        ]);
    }

    public function storePending($id, $data)
    {
        $project = ProjectHeader::findOrFail($id);
        $statusId = Status::getId('pending');

        if (!$statusId) throw new \Exception('Status pending tidak ada');

        Pending::create([
            'project_header_id' => $id,
            'reason' => $data['reason'],
            'pending_start' => now(),
            'count_to_effective' => (bool)$data['count_to_effective'],
        ]);

        $project->update(['status_id' => $statusId]);

        ProjectDetail::logPending($project, $data, $statusId);
    }

    public function continueProject($id, $data)
    {
        $project = ProjectHeader::findOrFail($id);

        $pending = $project->pendings()->whereNull('pending_end')->latest()->first();
        if (!$pending) throw new \Exception('Tidak ada pending aktif');

        $pending->closePending($data);

        $statusId = Status::getId('in_progress');

        $minutes = $project->getAppliedPendingMinutes();

        $project->update([
            'status_id' => $statusId,
            'total_pending_minutes' => $minutes,
            'effective_end_date' => $project->calculateEffectiveEnd($minutes),
        ]);

        ProjectDetail::logContinue($project, $pending, $data, $statusId);
    }

    public function updateStatus($project, $data)
    {
        $project->updateStatusData($data);
    }

    public function history($project)
    {
        return [
            'project' => $project,
            'details' => $project->details()->with(['status','developer'])->get(),
            'pendings'=> $project->pendings()->get(),
        ];
    }
}