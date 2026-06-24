<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'manage-settings',
            'manage-activity-logs',
            'manage-files',
            'send-notifications',
            'view-dashboard',
            'view-devices',
            'update-device-status',
            'manage-repairs',
            'assign-devices',
            'view-damage-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 3. Create all roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $userRole       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $teacherRole    = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole    = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $technicianRole = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);


        // 4. Assign permissions to roles
        $superadminRole->syncPermissions(Permission::pluck('name')->all());
        $userRole->givePermissionTo('view-dashboard');
        $teacherRole->givePermissionTo('view-dashboard');
        $studentRole->givePermissionTo('view-dashboard');
        $technicianRole->givePermissionTo([
            'view-dashboard',
            'view-devices',
            'update-device-status',
            'manage-repairs',
            'assign-devices',
            'view-damage-reports',
        ]);
    }
}
