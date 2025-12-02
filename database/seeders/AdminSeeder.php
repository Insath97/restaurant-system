<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    protected static ?string $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['guard_name' => 'admin', 'name' => 'Super Admin']);

        $allPermissions = Permission::all();
        $role->syncPermissions($allPermissions);

        $admin = new Admin();
        $admin->image = '/image';
        $admin->name = 'Mohamed Insath';
        $admin->email = 'admin@gmail.com';
        $admin->password = static::$password ??= Hash::make('12345678');
        $admin->save();

        // Assign the Super Admin role to the newly created user
        $admin->assignRole('Super Admin');
    }
}
