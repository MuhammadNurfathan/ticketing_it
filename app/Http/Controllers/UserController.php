<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Notifiable;
    public function index()
    {
    $users = User::latest()->get();
    return view('master/users.index',compact('users'));
    }

    public function create()
    {
        $roles = role::all();
        $departments = Department::all();
        return view('master/users.create', compact('departments','roles'));
    }

    public function edit(User $user)
    {
        $roles = role::all();
        $departments = Department::all();
        return view('master/users.edit', compact('user', 'departments','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|string',
        ]);

        User::create($request->only('name','username', 'department_id','role_id', 'email', 'password'));
        return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'nullable|string',
        ]);

        $data = $request->only('name','username', 'department_id','role_id', 'email');
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }
        public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Users tidak dapat dihapus karena masih digunakan!');
        }
    }
}
