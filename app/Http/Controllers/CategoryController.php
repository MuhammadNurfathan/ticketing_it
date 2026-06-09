<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('master/problem_category.index', compact('categories'));
    }

    public function create()
    {
        return view('master/problem_category.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();
        Category::create($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Problem Category berhasil ditambahkan.');
    }

    public function show(Category $category)
    {
        return view('master/problem_category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('master/problem_category.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Problem Category berhasil diupdate.');
    }


public function destroy(Category $category)
{
    try {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Problem Category berhasil dihapus.');

    } catch (QueryException $e) {

        return redirect()
            ->route('categories.index')
            ->with('error', 'Problem Category tidak dapat dihapus karena masih digunakan pada data lain.');

    } catch (\Throwable $e) {

        return redirect()
            ->route('categories.index')
            ->with('error', 'Terjadi kesalahan saat menghapus Problem Category.');

    }
}
}