<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Precisa de um revendedor
        \App\Models\Revendedor::factory(1)->create();
    }
}
