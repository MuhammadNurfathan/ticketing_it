<?php

namespace App\Http\Controllers;

use App\Http\Requests\Departments\DepartmentStoreRequest;
use App\Http\Requests\Departments\DepartmentUpdateRequest;
use App\Models\Department;
use App\Models\Location;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('location')->latest()->get();
        return view('master/department.index', compact('departments'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('master/department.create', compact('locations'));
    }

    public function store(DepartmentStoreRequest $request)
    {
        $data = $request->validated();

        Department::create($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Data Department berhasil disimpan');
    }

    public function edit(Department $department)
    {
        $locations = Location::all();
        return view('master/department.edit', compact('department', 'locations'));
    }

    public function update(DepartmentUpdateRequest $request, Department $department)
    {
        $data = $request->validated();

        $department->update($data);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Data Department berhasil diperbarui');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();

            return redirect()
                ->route('departments.index')
                ->with('success', 'Department berhasil dihapus!');
        } catch (\Throwable $e) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'Department tidak dapat dihapus karena masih digunakan!');
        }
    }
}