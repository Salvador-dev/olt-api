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
        // Permission::create(['name' => 'Administrar permisos', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Administrar roles', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Administrar Usuarios', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Autorizar onus', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Editar registros', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Eliminar registros', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Crear registros', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Acceder a reportes', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Configurar sistema', 'guard_name' => 'sanctum']);
        // Permission::create(['name' => 'Ver pantallas', 'guard_name' => 'sanctum']);
 
        // Role::create(['name' => 'super admin', 'guard_name' => 'sanctum'])->syncPermissions(['Administrar sistema', 'Administrar permisos', 'Administrar roles', 'Administrar Usuarios', 'Autorizar onus', 'Editar registros', 'Eliminar registros', 'Crear registros', 'Acceder a reportes', 'Configurar sistema', 'Ver pantallas']);

        Role::create(['name' => 'super admin', 'guard_name' => 'sanctum'])->syncPermissions(['Administrar sistema']);

    
    }
}
