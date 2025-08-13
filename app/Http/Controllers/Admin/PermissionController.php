<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permission.index', compact('permissions'));
    }

    public function create() {}

    public function store(CreatePermissionRequest $request)
    {
        try {
            $permission = new Permission();
            $permission->group_name = $request->validated('group_name');
            $permission->name = $request->validated('name');
            $permission->guard_name = 'admin';
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully!',
                'permission' => $permission,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(UpdatePermissionRequest $request, string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->group_name = $request->validated('group_name');
            $permission->name = $request->validated('name');
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully!',
                'permission' => $permission,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permission',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response(['status' => 'success', 'message' => 'Permission deleted successfully!']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
