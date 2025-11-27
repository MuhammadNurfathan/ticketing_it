<?php

namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function index(){
        $locations = Location::latest()->get();
        return view('master/location.index', compact('locations'));
    }

    public function create(){
        return view('master/location.create');
    }

    public function store(Request $request){
        $request->validate([
            'location_name' => 'required|string|max:255|unique:locations,location_name',
        ]);

        Location::create([
            'location_name' => $request->location_name,
        ]);

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil ditambahkan!');
    }

    public function show(Location $location){
        return view('master/location.show', compact('location'));
    }

    public function edit(Location $location){
        return view('master/location.edit', compact('location'));
    }

    public function update(Request $request, Location $location){
        $request->validate([
            'location_name' => 'required|string|max:255|unique:locations,location_name,' . $location->id,
        ]);

        $location->update([
            'location_name' => $request->location_name,
        ]);

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil diupdate!');
    }

    public function destroy(Location $location){
        try {
            $location->delete();
            return redirect()->route('locations.index')
                ->with('success', 'Location berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('locations.index')
                ->with('error', 'Location tidak dapat dihapus karena masih digunakan!');
        }
    }
}
