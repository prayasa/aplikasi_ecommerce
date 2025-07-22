<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua data yang ada dari tabel categories
        $existingCategories = DB::table('categories')->get()->toArray();

        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sebelum mengisi kembali
        DB::table('categories')->truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Jika ada data, masukkan kembali
        if (!empty($existingCategories)) {
            $dataToInsert = array_map(function ($category) {
                return (array)$category;
            }, $existingCategories);

            DB::table('categories')->insert($dataToInsert);
        }
    }
}