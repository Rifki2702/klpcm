<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $role_admin = Role::updateOrCreate(
            [
                'name' => 'admin',
            ],
            ['name' => 'admin']
        );

        $role_rm = Role::updateOrCreate(
            [
                'name' => 'rm',
            ],
            ['name' => 'rm']
        );
        
        $role_dokter = Role::updateOrCreate(
            [
                'name' => 'dokter',
            ],
            ['name' => 'dokter']
        );

        $permission = Permission::updateOrCreate(
            [
                'name' => 'view_dashboard',
            ],
            ['name' => 'view_dashboard']
        );

        $role_admin->givePermissionTo($permission);

        $permission2 = Permission::updateOrCreate(
            [
                'name' => 'view_table_on_dashboard',
            ],
            ['name' => 'view_table_on_dashboard']
        );

        $permission3 = Permission::updateOrCreate(
            [
                'name' => 'sidebar_admin',
            ],
            ['name' => 'sidebar_admin']
        );

        $permission6 = Permission::updateOrCreate(
            [
                'name' => 'sidebar_rm',
            ],
            ['name' => 'sidebar_rm']
        );

        $permission5 = Permission::updateOrCreate(
            [
                'name' => 'sidebar_dokter',
            ],
            ['name' => 'sidebar_dokter']
        );

        $role_admin->givePermissionTo($permission);
        $role_admin->givePermissionTo($permission2);
        $role_admin->givePermissionTo($permission3);
        $role_admin->givePermissionTo($permission6);
        $role_rm->givePermissionTo($permission);
        $role_rm->givePermissionTo($permission2);
        $role_rm->givePermissionTo($permission6);
        $role_dokter->givePermissionTo($permission);
        $role_dokter->givePermissionTo($permission5);

        $user   = User::find(1);
        $user2  = User::find(2);
        $user4  = User::find(10);
        $user5  = User::find(11);

    }
}
