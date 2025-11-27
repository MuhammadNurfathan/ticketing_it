<?php

namespace App\Http\Controllers;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index(){
        $priorities = Priority::latest()->get();
        return view('master/priority.index', compact('priorities'));
    }

    public function create(){
        return view('master/priority.create');
    }

    public function store(Request $request){
        Priority::create(['priority_name' => $request->priority_name,]);
        return redirect()->route('priority.index');
    }

    public function edit(Priority $priority){
        return view('master/priority.edit', compact('priority'));
    }

    public function update(Request $request, Priority $priority){
        $priority->update(['priority_name' => $request->priority_name,]);
        return redirect()->route('priority.index');
    }

    public function destroy(Priority $priority){
        $priority->delete();
        return redirect()->route('priority.index');
    }
}
