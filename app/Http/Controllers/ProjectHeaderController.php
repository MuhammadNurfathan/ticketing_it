<?php

namespace App\Http\Controllers;

use App\Models\ProjectHeader;
use Illuminate\Http\Request;

class ProjectHeaderController extends Controller
{
    public function index(Request $request){
        $start = $request->start_date;
        $end = $request->end_date;
        $filter = ProjectHeader::betweenRequestDates($start, $end);
        $stats = ProjectHeader::statistik($start,$end);

         // 🔹 Ambil masing-masing status dari query dasar
        $waitingProject    = (clone $filter)->waiting()->get();
        $inProgressProject = (clone $filter)->inProgress()->get();
        $voidProject       = (clone $filter)->void()->get();
        $doneProject       = (clone $filter)->done()->get();
        $pendingProject      = (clone $filter)->pending()->get();

        return view('project/header/index', compact(
            'stats',
            'waitingProject',
            'pendingProject',
            'inProgressProject',
            'voidProject',
            'doneProject',
            'start',
            'end',
        ));

    }

    public function create(){
        
    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }
}
