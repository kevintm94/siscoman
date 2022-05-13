<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = new User();
        $usuario->nombres = 'KEVIN';
        $usuario->apellidos = 'TRUJILLO MARTINEZ';
        $usuario->celular = '65305821';
        $usuario->direccion = 'N/A';
        $usuario->usuario = 'kevin';
        $usuario->rol = 'ADMINISTRADOR';
        $usuario->estado = 1;
        $usuario->password = Hash::make('administrador');
        $usuario->save();
    }
}
