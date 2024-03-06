<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::create(['name' => 'Crear permisos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Eliminar permisos', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'Ver pantallas', 'guard_name' => 'sanctum']);

        Role::create(['name' => 'admin', 'guard_name' => 'sanctum'])->syncPermissions(['Crear permisos', 'Eliminar permisos', 'Ver pantallas']);
        Role::create(['name' => 'usuario', 'guard_name' => 'sanctum'])->syncPermissions(['Crear permisos', 'Ver pantallas']);
        Role::create(['name' => 'consulta', 'guard_name' => 'sanctum'])->syncPermissions(['Ver pantallas']);
    }
}
