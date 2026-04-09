<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
    
        $empresa1 = Empresa::create([
            'nombre_empresa' => 'Tech Solutions S.L.',
            'cif'            => 'B12345678',
            'direccion'      => 'Calle Falsa 123, Madrid',
            'telefono'       => '600123456',
            'email_contacto' => 'contacto@empresa1.com',
            'activo'         => true,
        ]);

        $empresa2 = Empresa::create([
            'nombre_empresa' => 'Funeraria JJ S.A.',
            'cif'            => 'B87654321',
            'direccion'      => 'Calle Verdadera 321, Navarra',
            'telefono'       => '605432106',
            'email_contacto' => 'contacto@empresa2.com',
            'activo'         => true,
        ]);

        // 2. Creamos el usuario ADMIN (CEO) - Sin empresa asignada
        User::create([
            'nombre'     => 'Admin',
            'apellidos'  => 'Admin',
            'email'      => 'admin@gmail.com',
            'password_hash'   => Hash::make('1234'), // La contraseña será: password
            'rol'        => 'admin',
            'activo'     => true,
        ]);

        User::create([
            'nombre'     => 'Carlos',
            'apellidos'  => 'Perez Adánez',
            'email'      => 'cliente1@gmail.com',
            'password_hash'   => Hash::make('1234'),
            'rol'        => 'cliente',
            'id_empresa' => $empresa1->id_empresa,
            'activo'     => true,
        ]);

        User::create([
            'nombre'     => 'Ana',
            'apellidos'  => 'Ruíz Martínez',
            'email'      => 'cliente2@test.com',
            'password_hash'   => Hash::make('1234'),
            'rol'        => 'cliente',
            'id_empresa' => $empresa1->id_empresa,
            'activo'     => true,
        ]);

        User::create([
            'nombre'     => 'Carmen',
            'apellidos'  => 'Roig Latre',
            'email'      => 'cliente3@test.com',
            'password_hash'   => Hash::make('1234'),
            'rol'        => 'cliente',
            'id_empresa' => $empresa2->id_empresa,
            'activo'     => true,
        ]);

        User::create([
            'nombre'     => 'Luis',
            'apellidos'  => 'Espinosa Gómez',
            'email'      => 'cliente4@test.com',
            'password_hash'   => Hash::make('1234'),
            'rol'        => 'cliente',
            'id_empresa' => $empresa2->id_empresa,
            'activo'     => true,
        ]);

    }
}