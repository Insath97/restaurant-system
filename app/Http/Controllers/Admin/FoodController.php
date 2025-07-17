<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Category;
use App\Models\Menu;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $categories = Category::all();
        $menus = Menu::with('category')->get();
        return view('admin.menu.index', compact('categories', 'menus'));
    }

    public function create() {}

    public function store(CreateMenuRequest $request)
    {
        try {
            $imagePath = $this->handleFileUpload($request, 'image', null, 'menu', 'menu_item');

            if (!$imagePath) {
                return redirect()->back()
                    ->with('error', 'Failed to upload image. Please try again.')
                    ->withInput();
            }

            $menu = new Menu();
            $menu->category_id = $request->validated('category_id');
            $menu->name = $request->validated('name');
            $menu->slug = Str::slug($request->validated('name'));
            $menu->code = $request->validated('code');
            $menu->image = $imagePath ?? "/image";
            $menu->price = $request->validated('price');
            $menu->description = $request->validated('description') ?? null;
            $menu->is_featured = $request->validated('is_featured') ? true : false;
            $menu->save();

            return response()->json([
                'success' => true,
                'message' => 'Menu created successfully!',
                'menu' => $menu,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create menu item',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $menus = Menu::with('category')->findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Menu item retrieved successfully',
            'data' => $menus
        ], 200);
    }

    public function edit(string $id)
    {
        $menu = Menu::with('category')->findOrFail($id);
        $categories = Category::all();

        return response()->json([
            'status' => true,
            'menu' => $menu,
            'categories' => $categories
        ]);
    }

    public function update(UpdateMenuRequest $request, string $id)
    {
        try {
            $menu = Menu::findOrFail($id);

            $imagePath = $this->handleFileUpload($request, 'image', $menu->image, 'menu', 'menu_item');

            $menu->category_id = $request->validated('category_id');
            $menu->name = $request->validated('name');
            $menu->slug = Str::slug($request->validated('name'));
            $menu->code = $request->validated('code');
            $menu->image = $menu->image ?? $imagePath;
            $menu->price = $request->validated('price');
            $menu->description = $request->validated('description') ?? null;
            $menu->is_featured = $request->validated('is_featured') ? true : false;
            $menu->save();

            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully!',
                'menu' => $menu,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu item',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $menus = Menu::findOrFail($id);
            $this->deleteFile($menus->image);
            $menus->delete();

            return response([
                'status' => 'success',
                'message' => 'Menu item deleted successfully!'
            ]);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'message' => 'Failed to delete menu item: ' . $th->getMessage()
            ]);
        }
    }
}
