<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua data yang ada dari tabel products
        $existingProducts = DB::table('products')->get()->toArray();

        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sebelum mengisi kembali
        DB::table('products')->truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Jika ada data, masukkan kembali
        if (!empty($existingProducts)) {
             $dataToInsert = array_map(function ($product) {
                return (array)$product;
            }, $existingProducts);

            DB::table('products')->insert($dataToInsert);
        }
    }
}