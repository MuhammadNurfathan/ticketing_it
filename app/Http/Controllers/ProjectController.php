<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProgressRequest;
use App\Http\Requests\Project\DoneProjectRequest;
use App\Http\Requests\Project\PendingRequest;
use App\Http\Requests\Project\ContinueRequest;
use App\Services\ProjectService;
use App\Models\ProjectHeader;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('project.index', $this->service->getIndexData($request));
    }

    public function store(StoreProjectRequest $request)
    {
        $this->service->store($request->validated());

        return back()->with('success', 'Project berhasil ditambahkan.');
    }

    public function update(UpdateProgressRequest $request, ProjectHeader $project)
    {
        $this->service->updateProgress($project, $request->validated());

        return back()->with('success', 'Progress berhasil diupdate.');
    }

    public function done(DoneProjectRequest $request, ProjectHeader $project)
    {
        $this->service->done($project, $request->validated());

        return back()->with('success', 'Project selesai.');
    }

    public function updateProgress(ProjectHeader $project)
    {
        $this->service->startProject($project);

        return back();
    }

    public function storePending(PendingRequest $request, $id)
    {
        $this->service->storePending($id, $request->validated());

        return back()->with('success', 'Pending disimpan.');
    }

    public function continueProgress(ContinueRequest $request, $id)
    {
        $this->service->continueProject($id, $request->validated());

        return back()->with('success', 'Project dilanjutkan.');
    }

    public function updateStatus(Request $request, ProjectHeader $project)
    {
        $this->service->updateStatus($project, $request->all());

        return back()->with('success', 'Status diupdate.');
    }

    public function history(ProjectHeader $project)
    {
        return view('project.history', $this->service->history($project));
    }
}