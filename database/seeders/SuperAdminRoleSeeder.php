<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'Administrar sistema', 'guard_name' => 'sanctum']);
 
        Role::create(['name' => 'super admin', 'guard_name' => 'sanctum'])->syncPermissions(['Administrar sistema']);
    
    }
}
