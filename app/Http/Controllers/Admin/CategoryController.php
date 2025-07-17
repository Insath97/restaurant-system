<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return view('admin.category.index', compact('category'));
    }

    public function create() {}

    public function store(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            $category = new Category();
            $category->name = $validated['name'];
            $category->code = $validated['code'];
            $category->description = $validated['description'] ?? null;
            $category->save();

            toast('Category created successfully!', 'success');
            return redirect()->route('admin.categories.index');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create category. Please try again.')
                ->withInput();
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            $category = Category::findOrFail($id);
            $category->name = $validated['name'];
            $category->code = $validated['code'];
            $category->description = $validated['description'] ?? null;
            $category->save();

            toast('Category updated successfully!', 'success');
            return redirect()->route('admin.categories.index');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update category. Please try again.')
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response(['status' => 'success', 'message' => 'Category deleted successfully!']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
