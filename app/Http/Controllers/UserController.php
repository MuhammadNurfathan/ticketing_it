<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        return view('users.create', compact('departments'));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        return view('users.edit', compact('user', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create($request->only('name', 'department_id', 'email', 'password'));
        return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only('name', 'department_id', 'email');
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }
}
