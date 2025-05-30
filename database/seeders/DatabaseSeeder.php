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
        // Hapus dulu User factory bawaan kalau mau pakai seeder manual
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Daftarkan seeder tambahan di bawah ini
        $this->call([
            UsersTableSeeder::class,
            // Kalau ada seeder lain bisa ditambahkan di sini juga
        ]);
    }
}
