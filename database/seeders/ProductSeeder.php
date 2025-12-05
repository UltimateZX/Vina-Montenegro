<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('products')->insert([
            [
                'name' => 'Vino Tinto Malbec',
                'description' => 'Un vino robusto con notas de frutas rojas.',
                'price' => 45.00,
                'stock' => 50,
                'image_url' => '/img/vinos/tinto_malbec.jpg',
                'category_id' => 1, // Vinos Tintos
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vino Blanco Sauvignon',
                'description' => 'Fresco y cítrico, ideal para mariscos.',
                'price' => 55.00,
                'stock' => 30,
                'image_url' => '/img/vinos/blanco_sauvignon.jpg',
                'category_id' => 2, // Vinos Blancos
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisco Quebranta',
                'description' => 'Pisco puro de uva Quebranta, perfecto para Pisco Sour.',
                'price' => 75.00,
                'stock' => 40,
                'image_url' => '/img/piscos/quebranta.jpg',
                'category_id' => 3, // Pisco
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
