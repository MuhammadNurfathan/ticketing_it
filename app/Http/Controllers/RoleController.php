<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('master/roles.index', compact('roles'));
    }

    public function create()
    {
        return view('master/roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name',
        ]);

        try {
            Role::create([
                'role_name' => $request->role_name,
            ]);

            return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
    }

    public function show(Role $role)
    {
        return view('master/roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('master/roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $role->id,
        ]);

        try {
            $role->update([
                'role_name' => $request->role_name,
            ]);

            return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            // Check if role has users
            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user');
            }

            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}