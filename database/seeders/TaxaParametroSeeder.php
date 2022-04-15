<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxaParametroSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\TaxaParametro::factory(30)->create();
    }
}
