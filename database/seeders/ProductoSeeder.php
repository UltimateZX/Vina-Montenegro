<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- ¡Importa la clase DB!

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'categoria_id' => 1, // Vino Tinto
                'nombre' => 'Viña Montenegro - Tinto',
                'descripcion' => 'Hechos con las mejores uvas de Quilmamná',
                'precio' => 15.00,
                'stock' => 50,
                'url_imagen' => '/imagenes/vino-malbec.jpg',
                'fecha_creacion' => now(),
            ],
            [
                'categoria_id' => 2, // Vino Blanco
                'nombre' => 'Viña Montenegro - Blanco',
                'descripcion' => 'Aprobado por Majo',
                'precio' => 15.00,
                'stock' => 30,
                'url_imagen' => '/imagenes/vino-blanc.jpg',
                'fecha_creacion' => now(),
            ],
            [
                'categoria_id' => 3, // Pisco
                'nombre' => 'Pisco Acholado',
                'descripcion' => 'Pisco acholado de uvas seleccionadas.',
                'precio' => 20.00,
                'stock' => 25,
                'url_imagen' => '/imagenes/pisco-acholado.jpg',
                'fecha_creacion' => now(),
            ],
            [
                'categoria_id' => 1, // Vino Tinto
                'nombre' => 'Vino Borgoña',
                'descripcion' => 'Vino tinto dulce y ligero.',
                'precio' => 25.00,
                'stock' => 100,
                'url_imagen' => '/imagenes/vino-borgona.jpg',
                'fecha_creacion' => now(),
            ],
        ]);
    }
}