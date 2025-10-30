<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Asegúrate de que SOLO contenga esto:
        $this->call([
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}