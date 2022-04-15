<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\FormaPagamento::create(['descricao' => 'DINHEIRO']);
        \App\Models\FormaPagamento::create(['descricao' => 'CARTÃO DÉBITO']);
        \App\Models\FormaPagamento::create(['descricao' => 'CARTÃO CRÉDITO']);
        \App\Models\FormaPagamento::create(['descricao' => 'PIX']);
    }
}
