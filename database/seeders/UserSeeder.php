<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $tenant = explode('tenant', $currentDB)[1];

        $user = User::create([
            'name' => 'Thomas ADMIN',
            'email' => 'admin@' . $tenant . '.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('admin');

        DB::connection('mysql')->insert('insert into login_emails (email, company) values (?, ?)', [$user->email, $tenant]);

        $user = User::create([
            'name' => 'Thomas USUARIO',
            'email' => 'usuario@' . $tenant . '.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('usuario');

        DB::connection('mysql')->insert('insert into login_emails (email, company) values (?, ?)', [$user->email, $tenant]);

        $user = User::create([
            'name' => 'Thomas CONSULTA',
            'email' => 'consulta@' . $tenant . '.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('consulta');

        DB::connection('mysql')->insert('insert into login_emails (email, company) values (?, ?)', [$user->email, $tenant]);

    }
}
