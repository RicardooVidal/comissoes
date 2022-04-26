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
        \App\Models\ComissaoParametro::create([
            'descricao' => 'MERCADO LIVRE PADRÃO',
            'comissao_percentual' => 0.10,
            'comissao_indicado_percentual' => 0.03,
            'ativo' => true
        ]);
    }
}
