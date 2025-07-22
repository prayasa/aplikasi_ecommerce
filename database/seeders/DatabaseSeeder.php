<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class, // Pastikan nama kelasnya benar
        ]);

        // Panggil seeder kategori terlebih dahulu
        $this->call(CategoriesTableSeeder::class);

        // Kemudian panggil seeder produk
        $this->call(ProductsTableSeeder::class);
    }
}