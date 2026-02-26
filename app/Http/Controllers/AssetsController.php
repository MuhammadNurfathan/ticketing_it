<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assets\AssetStoreRequest;
use App\Http\Requests\Assets\AssetUpdateRequest;
use App\Models\Assets;

class AssetsController extends Controller
{
    public function index()
    {
        $assets = Assets::latest()->get();
        return view('master/assets.index', compact('assets'));
    }

    public function create()
    {
        return view('master/assets.create');
    }

    public function store(AssetStoreRequest $request)
    {
        $data = $request->validated();
        Assets::create($data);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function edit(Assets $asset)
    {
        return view('master/assets.edit', compact('asset'));
    }

    public function update(AssetUpdateRequest $request, Assets $asset)
    {
        $data = $request->validated();
        $asset->update($data);

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Assets $asset)
    {
        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil dihapus.');
    }
}