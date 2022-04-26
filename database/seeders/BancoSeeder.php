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
            '001' => "BANCO DO BRASIL S.A (BB)",
            '237' => "BANCO BRADESCO S.A",
            '335' => "BANCO DIGIO S.A",
            '260' => "NU PAGAMENTOS S.A (NUBANK)",
            '290' => "PAGSEGURO INTERNET S.A (PagBank)",
            '380' => "PICPAY SERVICOS S.A",
            '323' => "MERCADO PAGO",
            '1237' => "NEXT BANK (BRADESCO)",
            '077' => "BANCO INTER S.A",
            '096' => "BANCO B3 S.A",
            '341' => "ITAÚ UNIBANCO S.A",
            '104' => "CAIXA ECONÔMICA FEDERAL (CEF)",
            '033' => "BANCO SANTANDER BRASIL S.A",
            '212' => "BANCO ORIGINAL S.A",
            '756' => "BANCOOB (BANCO COOPERATIVO DO BRASIL)",
            '389' => "BANCO MERCANTIL DO BRASIL S.A",
            '422' => "BANCO SAFRA S.A",
            '739' => "BANCO CETELEM S.A",
            '743' => "BANCO SEMEAR S.A",
            '712' => "BANCO OURINVEST S.A",
            '336' => "BANCO C6 S.A - C6 BANK",
            '654' => "BANCO DIGIMAIS S.A",
            '999' => "NÃO INFORMADO"
        ];

        foreach ($bancos as $key => $banco) {
            \App\Models\Banco::create([
                'id' => $key,
                'descricao' => $banco
            ]);
        }
    }
}
