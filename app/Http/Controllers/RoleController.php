<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\RoleStoreRequest;
use App\Http\Requests\Roles\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();
        return view('master/roles.index', compact('roles'));
    }

    public function create()
    {
        return view('master/roles.create');
    }

    public function store(RoleStoreRequest $request)
    {
        $data = $request->validated();
        Role::create($data);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        return view('master/roles.edit', compact('role'));
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update($data);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }


public function destroy(Role $role)
{
    try {
        if ($role->users()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('roles.index')
            ->with('error', 'Role tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Throwable $e) {

        return redirect()
            ->route('roles.index')
            ->with('error', 'Terjadi kesalahan saat menghapus role.');

    }
}
}