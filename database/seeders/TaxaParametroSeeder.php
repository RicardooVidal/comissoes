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
        \App\Models\TaxaParametro::create([
            'descricao' => 'MERCADO LIVRE PADRÃO',
            'taxa_percentual' => 0.08,
            'ativo' => true
        ]);
    }
}
