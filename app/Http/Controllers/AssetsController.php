<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assets\AssetStoreRequest;
use App\Http\Requests\Assets\AssetUpdateRequest;
use App\Models\Assets;
use Illuminate\Database\QueryException;

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
    try {
        $asset->delete();

        return redirect()
            ->route('assets.index')
            ->with('success', 'Data aset berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('assets.index')
            ->with('error', 'Data aset tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Throwable $e) {

        return redirect()
            ->route('assets.index')
            ->with('error', 'Terjadi kesalahan saat menghapus data aset.');

    }
}
}