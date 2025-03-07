<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            ['provincia' => 'Azuay'],
            ['provincia' => 'Bolívar'],
            ['provincia' => 'Cañar'],
            ['provincia' => 'Carchi'],
            ['provincia' => 'Cotopaxi'],
            ['provincia' => 'Chimborazo'],
            ['provincia' => 'El Oro'],
            ['provincia' => 'Esmeraldas'],
            ['provincia' => 'Guayas'],
            ['provincia' => 'Imbabura'],
            ['provincia' => 'Loja'],
            ['provincia' => 'Los Rios'],
            ['provincia' => 'Manabi'],
            ['provincia' => 'Morona Santiago'],
            ['provincia' => 'Napo'],
            ['provincia' => 'Pastaza'],
            ['provincia' => 'Pichincha'],
            ['provincia' => 'Tungurahua'],
            ['provincia' => 'Zamora Chinchipe'],
            ['provincia' => 'Galápagos'],
            ['provincia' => 'Sucumbíos'],
            ['provincia' => 'Orellana'],
            ['provincia' => 'Santo Domingo de Los Tsáchilas'],
            ['provincia' => 'Santa Elena'],
            ['provincia' => 'Zonas No Delimitadas'],
        ];

        DB::table('provincia')->insert($provincias);
    }
}
