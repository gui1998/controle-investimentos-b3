<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(  StockTypeSeeder::class);
        $this->call(  IncomeTypeSeeder::class);
        $this->call(  SectorSeeder::class);
        $this->call(  RoleTableSeeder::class);
    }
}
