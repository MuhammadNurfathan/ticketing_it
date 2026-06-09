<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
 use Illuminate\Database\QueryException;
class UserController extends Controller
{
    public function index()
    {
        $users = User::getUser();

        return view('master/users.index', compact('users'));
    }

    public function create()
    {
        $data = User::getRolesAndDepartments();

        return view('master/users.create', [
            'roles' => $data['roles'],
            'departments' => $data['departments'],
        ]);
    }

    public function edit(User $user)
    {
        $data = User::getRolesAndDepartments();

        return view('master/users.edit', [
            'user' => $user,
            'roles' => $data['roles'],
            'departments' => $data['departments'],
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

 

public function destroy(User $user)
{
    try {

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('users.index')
            ->with('error', 'User tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Exception $e) {

        return redirect()
            ->route('users.index')
            ->with('error', 'Terjadi kesalahan saat menghapus user.');

    }
}
}
