<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bancos = [
            001 => "001 - Banco do Brasil S.A.", 
            033 => "033 - Banco Santander (Brasil) S.A.",
            104 => "104 - Caixa Econômica Federal",
            237 => "237 - Banco Bradesco S.A.",
            341 => "341 - Banco Itaú S.A.",
            356 => "356 - Banco Real S.A. (antigo)",
            389 => "389 - Banco Mercantil do Brasil S.A.",
            399 => "399 - HSBC Bank Brasil S.A. – Banco Múltiplo",
            422 => "422 - Banco Safra S.A.",
            453 => "453 - Banco Rural S.A.",
            633 => "633 - Banco Rendimento S.A.",
            652 => "652 - Itaú Unibanco Holding S.A.",
            745 => "745 - Banco Citibank S.A."
        ];

        foreach ($bancos as $key => $banco) {
            \App\Models\Banco::create([
                'id' => $key,
                'descricao' => $banco
            ]);
        }
    }
}
