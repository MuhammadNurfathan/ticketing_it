<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index (){
    $departments = Department::with('location')->paginate(10);
        return view('master/department.index',compact('departments'));
    }

    public function create() {
        $locations = location::all();
        return view('master/department.create',compact('locations'))
        ->with('success','Department Berhasil Ditambahkan');
    }

    public function store(Request $request){
        $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,department_name',
            'location_id' => 'required|exists:locations,id'

        ]);

        Department::create([
            'department_name' => $request->department_name,
            'location_id' => $request->location_id,
        ]);
        return redirect()->route('departments.index')->with('success','Data Department Berhasil Disimpan');

    }

    public function edit(Department $department){
          $locations = location::all();
        return view('master/department.edit',compact('department','locations'))
        ->with('success','Department Berhasil Ditambahkan');
    }

    public function update(Request $request,Department $department){
            $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,department_name,'. $department->id,
            'location_id' => 'required|exists:locations,id' 

        ]);

        $department->update([
            'department_name' => $request->department_name,
            'location_id' => $request->location_id,
        ]);
        return redirect()->route('departments.index')->with('success','Data Department Berhasil Disimpan');
    }

      public function destroy(Department $department)
    {
        try {
            $department->delete();
            return redirect()->route('departments.index')
                ->with('success', 'Department berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')
                ->with('error', 'Department tidak dapat dihapus karena masih digunakan!');
        }
    }
}
