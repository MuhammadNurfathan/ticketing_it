<?php
namespace App\Http\Controllers; 
use App\Models\ProblemCategory;
use Illuminate\Http\Request;

class ProblemCategoryController extends Controller
{
    public function index(){
        $problemCategories = ProblemCategory::latest()->get();
        return view('master/problem_category.index', compact('problemCategories'));
    }

    public function create(){
        return view('master/problem_category.create');
    }

    public function store(Request $request){
        $request->validate([
            'problem_category_name' => 'required|string|max:50',
        ]);

        try {
            ProblemCategory::create([
                'problem_category_name' => $request->problem_category_name,
            ]);

            return redirect()->route('problem_categories.index')
                ->with('success', 'Problem Category berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan Problem Category: ' . $e->getMessage());
        }
    }

    public function show(ProblemCategory $problemCategory){
        return view('master/problem_category.show', compact('problemCategory'));
    }

    public function edit(ProblemCategory $problemCategory){
        return view('master/problem_category.edit', compact('problemCategory'));
    }

    public function update(Request $request, ProblemCategory $problemCategory){
        $request->validate([
            'problem_category_name' => 'required|string|max:50',
        ]);

        try {
            $problemCategory->update([
                'problem_category_name' => $request->problem_category_name,
            ]);

            return redirect()->route('problem_categories.index')
                ->with('success', 'Problem Category berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate Problem Category: ' . $e->getMessage());
        }
    }

    public function destroy(ProblemCategory $problemCategory){
        try {
            $problemCategory->delete();
            return redirect()->route('problem_categories.index')
                ->with('success', 'Problem Category berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('problem_categories.index')
                ->with('error', 'Problem Category tidak dapat dihapus: ' . $e->getMessage());
        }
    }
}