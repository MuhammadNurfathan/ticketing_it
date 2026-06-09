<?php

namespace App\Http\Controllers;

use App\Http\Requests\Locations\LocationStoreRequest;
use App\Http\Requests\Locations\LocationUpdateRequest;
use App\Models\Location;
use Illuminate\Database\QueryException;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->get();
        return view('master/location.index', compact('locations'));
    }

    public function create()
    {
        return view('master/location.create');
    }

    public function store(LocationStoreRequest $request)
    {
        $data = $request->validated();
        Location::create($data);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil ditambahkan.');
    }

    public function show(Location $location)
    {
        return view('master/location.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('master/location.edit', compact('location'));
    }

    public function update(LocationUpdateRequest $request, Location $location)
    {
        $data = $request->validated();
        $location->update($data);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil diperbarui.');
    }


public function destroy(Location $location)
{
    try {
        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('locations.index')
            ->with('error', 'Location tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Throwable $e) {

        return redirect()
            ->route('locations.index')
            ->with('error', 'Terjadi kesalahan saat menghapus location.');

    }
}
}