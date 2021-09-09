<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationTypeSeeder extends Seeder
{
    static $operationTypes = [
        'Day Trade',
        'Swing Trade',
        'Position Trade',
        'Scalping',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$operationTypes as $type) {
            DB::table('operation_types')->insert([
                'name' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
