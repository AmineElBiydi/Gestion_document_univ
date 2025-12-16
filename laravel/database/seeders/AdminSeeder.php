<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'identifiant' => 'admin',
            'nom' => 'Administrateur',
            'prenom' => 'Principal',
            'email' => 'admin@universite.ma',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'est_actif' => true,
        ]);

        Admin::create([
            'identifiant' => 'admin2',
            'nom' => 'Admin',
            'prenom' => 'Scolarité',
            'email' => 'scolarite@universite.ma',
            'password' => Hash::make('password'),
            'role' => 'scolarite',
            'est_actif' => true,
        ]);
    }
}
