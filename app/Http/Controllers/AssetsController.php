<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function index()
    {
        $assets = Assets::latest()->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assets_code' => 'required|max:255|unique:assets',
            'assets_name' => 'required|max:255',
            'category'    => 'required|max:255',
            'status'      => 'required|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/assets'), $fileName);
            $data['image'] = $fileName;
        }

        Assets::create($data);

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function edit(Assets $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Assets $asset)
    {
        $request->validate([
            'assets_code' => 'required|max:255|unique:assets,assets_code,' . $asset->id,
            'assets_name' => 'required|max:255',
            'category'    => 'required|max:255',
            'status'      => 'required|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/assets'), $fileName);
            $data['image'] = $fileName;
        }

        $asset->update($data);

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Assets $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Data aset berhasil dihapus.');
    }
}
