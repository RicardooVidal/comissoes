<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComissaoParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ComissaoParametro::factory(30)->create();
    }
}
