<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- ¡Importa la clase DB!

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos DB::table() para insertar los datos
        DB::table('categorias')->insert([
            [
                'nombre' => 'Vino Tinto',
                'descripcion' => 'Vinos tintos de la casa.',
            ],
            [
                'nombre' => 'Vino Blanco',
                'descripcion' => 'Vinos blancos frescos y aromáticos.',
            ],
            [
                'nombre' => 'Pisco',
                'descripcion' => 'Destilados premium.',
            ],
        ]);
    }
}