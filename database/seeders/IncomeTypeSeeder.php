<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomeTypeSeeder extends Seeder
{
    static $incomeTypes = [
        'JCP',
        'Dividendos',
        'Bonificação',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$incomeTypes as $type) {
            DB::table('income_types')->insert([
                'name' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
