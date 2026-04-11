<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Tickets\TicketStoreRequest;
use App\Http\Requests\Tickets\TicketUpdateRequest;
use App\Http\Requests\Tickets\TicketUserUpdateRequest;
use App\Http\Requests\Tickets\TicketStatusRequest;

class TicketsController extends Controller
{
    protected $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view(
            'tickets/DashboardTicketsAdmin',
            $this->service->getAdminDashboard($request)
        );
    }

    public function indexUser()
    {
        return view(
            'tickets.DashboardTicketsUser',
            $this->service->getUserDashboard(Auth::id())
        );
    }

    public function create()
    {
        return view(
            'tickets.CreateAdmin',
            $this->service->getFormData()
        );
    }

    public function createUser()
    {
        return $this->service->handleCreateUser(Auth::id());
    }

    public function store(TicketStoreRequest $request)
    {
        $this->service->store($request);

        return redirect()->route(
            $request->from === 'user'
                ? 'DashboardTicketsUser.index'
                : 'DashboardTicketsAdmin.index'
        )->with('success', '✅ Ticket berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return view(
            'tickets.EditAdmin',
            $this->service->edit($id)
        );
    }

    public function editUser($id)
    {
        return view(
            'tickets.EditUser',
            $this->service->editUser($id)
        );
    }

    public function updateUser(TicketUserUpdateRequest $request, $id)
    {
        $this->service->updateUser($id, $request->validated());

        return redirect()->route('DashboardTicketsUser.index')
            ->with('success', '✅ Ticket berhasil diupdate!');
    }

    public function update(TicketUpdateRequest $request, $id)
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', '✅ Ticket berhasil diupdate!');
    }

    public function updateStatus(TicketStatusRequest $request, Ticket $ticket)
    {
        $this->service->updateStatus($ticket, $request->validated());

        return redirect()->route('DashboardTicketsAdmin.index')
            ->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function updateStatusDone(TicketStatusRequest $request, Ticket $ticket)
    {
        $this->service->updateStatusDone($ticket, $request->validated());

        return back()->with('success', 'Ticket updated successfully.');
    }
}
