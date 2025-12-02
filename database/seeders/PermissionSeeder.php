<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            /* Access Management */
            ['name' => 'Permission Index',  'group_name' => 'Access Management Permissions'],
            ['name' => 'Permission Create', 'group_name' => 'Access Management Permissions'],
            ['name' => 'Permission Update', 'group_name' => 'Access Management Permissions'],
            ['name' => 'Permission Delete', 'group_name' => 'Access Management Permissions'],
            ['name' => 'Role Index',  'group_name' => 'Access Management Permissions'],
            ['name' => 'Role Create', 'group_name' => 'Access Management Permissions'],
            ['name' => 'Role Update', 'group_name' => 'Access Management Permissions'],
            ['name' => 'Role Delete', 'group_name' => 'Access Management Permissions'],

            /* User Management */
            ['name' => 'User Index',  'group_name' => 'User Management Permissions'],
            ['name' => 'User Create', 'group_name' => 'User Management Permissions'],
            ['name' => 'User Update', 'group_name' => 'User Management Permissions'],
            ['name' => 'User Delete', 'group_name' => 'User Management Permissions'],

            /* Category Management */
            ['name' => 'Category Index',  'group_name' => 'Category Management Permissions'],
            ['name' => 'Category Create', 'group_name' => 'Category Management Permissions'],
            ['name' => 'Category Update', 'group_name' => 'Category Management Permissions'],
            ['name' => 'Category Delete', 'group_name' => 'Category Management Permissions'],

            /* Food/Menu Management */
            ['name' => 'Food Index',  'group_name' => 'Food Management Permissions'],
            ['name' => 'Food Create', 'group_name' => 'Food Management Permissions'],
            ['name' => 'Food Update', 'group_name' => 'Food Management Permissions'],
            ['name' => 'Food Delete', 'group_name' => 'Food Management Permissions'],

            /* Table Management */
            ['name' => 'Table Index',  'group_name' => 'Table Management Permissions'],
            ['name' => 'Table Create', 'group_name' => 'Table Management Permissions'],
            ['name' => 'Table Update', 'group_name' => 'Table Management Permissions'],
            ['name' => 'Table Delete', 'group_name' => 'Table Management Permissions'],

            /* Customer Management */
            ['name' => 'Customer Index',  'group_name' => 'Customer Management Permissions'],
            ['name' => 'Customer View', 'group_name' => 'Customer Management Permissions'],

            /* Reservation Management */
            ['name' => 'Reservation Index',  'group_name' => 'Reservation Management Permissions'],
            ['name' => 'Reservation View', 'group_name' => 'Reservation Management Permissions'],
            ['name' => 'Reservation Update', 'group_name' => 'Reservation Management Permissions'],

            /* Order Management */
            ['name' => 'Order Index',  'group_name' => 'Order Management Permissions'],
            ['name' => 'Order View', 'group_name' => 'Order Management Permissions'],
            ['name' => 'Order Update', 'group_name' => 'Order Management Permissions'],

            /* Review Management */
            ['name' => 'Review Index',  'group_name' => 'Review Management Permissions'],
            ['name' => 'Review View', 'group_name' => 'Review Management Permissions'],
            ['name' => 'Review Update', 'group_name' => 'Review Management Permissions'],
            ['name' => 'Review Delete', 'group_name' => 'Review Management Permissions'],

            /* Dashboard Access */
            ['name' => 'Dashboard Access', 'group_name' => 'Dashboard Permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'group_name' => $permission['group_name'],
                'guard_name' => 'admin',
            ]);
        }
    }
}
