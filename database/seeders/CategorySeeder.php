<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('categories')->insert([
            [
                'name' => 'Vinos Tintos',
                'description' => 'Vinos hechos con uvas tintas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vinos Blancos',
                'description' => 'Vinos hechos con las mejores uvas verdes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisco',
                'description' => 'El mejor aguardiente del Perú',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
