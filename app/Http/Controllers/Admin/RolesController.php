<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Role Index,admin'])->only(['index']);
        $this->middleware(['permission:Role Create,admin'])->only(['create', 'store']);
        $this->middleware(['permission:Role Update,admin'])->only(['edit', 'update']);
        $this->middleware(['permission:Role Delete,admin'])->only('destroy');
    }

    public function index()
    {
        $roles = Role::with('permissions')->where('guard_name', 'admin')->get();
        $permissionGroups = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy('group_name');

        return view('admin.roles.index', compact('roles', 'permissionGroups'));
    }

    public function store(CreateRoleRequest $request)
    {
        try {
            $validated = $request->validated();

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'admin'
            ]);

            // Sync permissions by ID
            $role->syncPermissions($validated['permissions']);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role: ' . $th->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->where('guard_name', 'admin')->findOrFail($id);
        $permissionGroups = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissionGroups' => $permissionGroups->map(function ($permissions, $groupName) {
                return [
                    'name' => $groupName,
                    'slug' => \Illuminate\Support\Str::slug($groupName),
                    'permissions' => $permissions
                ];
            })->values(),
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $role = Role::where('guard_name', 'admin')->findOrFail($id);
            $role->update(['name' => $request->validated('name')]);

            // Sync permissions by ID
            $role->syncPermissions($request->validated('permissions'));

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role: ' . $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->name === 'Super Admin') {
                throw new \Exception('Super Admin role cannot be deleted');
            }

            $role->delete();

            return response(['status' => 'success', 'message' => 'Role deleted successfully!']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
