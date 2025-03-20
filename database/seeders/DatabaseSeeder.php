<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Pusat MPM',
            'email' => 'pusatmpm@gmail.com',
            'role' => 'superadmin',
            'password' => bcrypt('12345678'),
        ]);
        User::factory()->create([
            'name' => 'Admin Provinsi',
            'email' => 'provinsi@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('12345678'),
        ]);
        User::factory()->create([
            'name' => 'Fasilitator',
            'email' => 'fasilitator@gmail.com',
            'role' => 'fasilitator',
            'password' => bcrypt('12345678'),
        ]);
    }
}
