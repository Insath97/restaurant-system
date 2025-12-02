<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Category Index,admin'])->only(['index']);
        $this->middleware(['permission:Category Create,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Category Update,admin'])->only(['edit', 'update']);
        $this->middleware(['permission:Category Delete,admin'])->only(['destroy']);
    }

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

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'menu' => $category,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category item',
                'error' => $th->getMessage()
            ], 500);
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

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'menu' => $category,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category item',
                'error' => $th->getMessage()
            ], 500);
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
