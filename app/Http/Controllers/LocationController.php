<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::latest()->paginate(10);
        return view('master/location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master/location.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255|unique:locations,location_name',
        ]);

        Location::create([
            'location_name' => $request->location_name,
        ]);

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return view('master/location.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('master/location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'location_name' => 'required|string|max:255|unique:locations,location_name,' . $location->id,
        ]);

        $location->update([
            'location_name' => $request->location_name,
        ]);

        return redirect()->route('locations.index')
            ->with('success', 'Location berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
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