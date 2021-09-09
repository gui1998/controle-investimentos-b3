<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    static $sectors = [
        'Bens Industriais',
        'Comunicações',
        'Consumo Cíclico',
        'Consumo não Cíclico',
        'Financeiro',
        'Materiais Básicos',
        'Outros',
        'Petróleo, Gás e Biocombustíveis',
        'Saúde',
        'Tecnologia da Informação',
        'Utilidade Pública',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$sectors as $sector) {
            DB::table('sectors')->insert([
                'name' => $sector,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
