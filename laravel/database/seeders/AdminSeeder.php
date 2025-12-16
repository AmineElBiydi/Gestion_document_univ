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
            'password' => Hash::make('admin123'),
        ]);

        Admin::create([
            'identifiant' => 'admin2',
            'password' => Hash::make('password'),
        ]);
    }
}
