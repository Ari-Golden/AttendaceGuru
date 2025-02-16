<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'view-user']);


        Permission::create(['name' => 'create-role']);
        Permission::create(['name' => 'edit-role']);
        Permission::create(['name' => 'delete-role']);
        Permission::create(['name' => 'view-role']);

        Permission::create(['name' => 'create-attendance']);
        Permission::create(['name' => 'edit-attendance']);
        Permission::create(['name' => 'delete-attendance']);
        Permission::create(['name' => 'view-attendance']);
        Permission::create(['name' => 'create-shift']);
        Permission::create(['name' => 'edit-shift']);
        Permission::create(['name' => 'delete-shift']);
        Permission::create(['name' => 'view-shift']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'guru']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo(Permission::all());
        $roleGuru = Role::findByName('guru');
        $roleGuru->givePermissionTo([
            'create-attendance',
            'edit-attendance',
            'delete-attendance',
            'view-attendance',
            'view-shift',
        ]);


    }
}
