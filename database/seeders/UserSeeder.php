<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'Thomas ADMIN',
            'email' => 'admin@admin',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name' => 'Thomas USUARIO',
            'email' => 'usuario@usuario',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('usuario');

        $user = User::create([
            'name' => 'Thomas CONSULTA',
            'email' => 'consulta@consulta',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('consulta');


    }
}
