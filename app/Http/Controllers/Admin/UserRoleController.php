<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRoleRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Mail\UserRoleCreateMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:User Index,admin'])->only(['index']);
        $this->middleware(['permission:User Create,admin'])->only(['create', 'store']);
        $this->middleware(['permission:User Update,admin'])->only(['edit', 'update']);
        $this->middleware(['permission:User Delete,admin'])->only('destroy');
    }
    
    public function index()
    {
        $users = Admin::all();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create() {}

    public function store(CreateUserRoleRequest $request)
    {
        try {

            $user = new Admin();
            $user->name =  $request->validated('name');
            $user->email = $request->validated('email');
            $user->password = bcrypt($request->validated('password'));
            $user->save();

            $user->assignRole($request->validated('role'));

            Mail::to($user->email)->send(
                new UserRoleCreateMail(
                    $request->validated('email'),
                    $request->validated('password'),
                    $request->validated('role')
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id) {}


    public function edit(string $id)
    {
        $user = Admin::findOrFail($id);
        $roles = Role::all();
        return response()->json([
            'user' => $user,
            'roles' => $roles
        ]);
    }


    public function update(UpdateUserRoleRequest $request, string $id)
    {
        try {
            $user = Admin::findOrFail($id);
            $user->name =  $request->validated('name');
            $user->email = $request->validated('email');

            if (isset($request->validated()['password']) && $request->validated()['password'] !== null) {
                $user->password = bcrypt($request->validated('password'));
            }
            $user->save();

            $user->syncRoles($request->validated('role'));

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = Admin::findOrFail($id);

            /* super admin validation */
            if ($user->getRoleNames()->first() === 'Super Admin') {
                return response(['status' => 'error', 'message' => 'Can\'t Deleted This is Default Role']);
            }
            $user->delete();

            return response(['status' => 'success', 'message' => 'User Deleted Successfully']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
