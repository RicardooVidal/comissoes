<?php

namespace Tests\Feature;

use App\Models\Comissao;
use App\Models\ComissaoParametro;
use App\Models\Configuracao;
use App\Models\TaxaParametro;
use App\Models\Venda;
use App\Models\VendaLucro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class VendaTest extends TestCase
{
    use WithoutMiddleware;
    use RefreshDatabase;
    use WithFaker;

    /**
     * Testa inclusão de venda de um revendedor com indicação expirada ou inexistente
     *
     * @return void
     */
    public function test_inclusao_venda_com_revendedor_com_indicacao_expirada_ou_inexistente()
    {
        // Alterar o campo validade_comissao_indicado para -1. Assim na validação da data de indicação vai retornar uma data inválida como 31/12/1969
        // Exemplo: 22/03/2022 - 1 = 31/12/1969
        // Dessa forma a comissão para o indicador NÃO deve ser considerada.
        $params = [
            'validade_comissao_indicado' => -1,
            'recuperar_descricao_compra' => 1
        ];

        $this->putJson('/api/configuracoes', $params)
            ->assertOk();

        $params = $this->getRevendedorParams();

        // Criar um revendedor que será o indicador
        $response = $this->postJson('/api/revendedor', $params);
        // Recuperar o id do indicador criado
        $idIndicador = $response['result']['id'];

        $response->assertCreated();

        // Criar outro revendedor com o campo indicador_id referenciando o id do revendedor acima
        $params = $this->getRevendedorParams();
        $params['indicador_id'] = $idIndicador;

        // Alterar alguns parâmetros para não retornar como duplicado
        $params['cpf'] = '97154580097';
        $params['celular'] = '(11) 88888-8888';

        // Criar o revendedor
        $response = $this->postJson('/api/revendedor', $params);
        // Recuperar id do revendedor com indicador
        $idRevendedor = $response['result']['id'];

        $response->assertCreated();
        $params = [
            'revendedor_id' => $idRevendedor,
            'taxa_parametro_id' => (TaxaParametro::create(TaxaParametro::factory()->make()->getAttributes()))->id, // taxa 8%
            'comissao_parametro_id' => (ComissaoParametro::create(ComissaoParametro::factory()->make()->getAttributes()))->id, // comissao revendedor 10%
            'outras_despesas_valor' => 11.50,
            'descricao' => $this->faker->text(),
            'preco_unitario' => 30.00,
            'quantidade' => 1
        ];

        // Criar venda
        $response = $this->postJson('/api/venda', $params);
        $idVenda = $response['result']['id'];
        $response->assertStatus(201);

        // Verificar se tabela venda_lucros foi populada com calculos da venda criada
        $vendaLucro = VendaLucro::where('venda_id', $idVenda)->first();
        $this->assertNotEmpty($vendaLucro);

        /** Assumindo que o valor total da venda é 30,00 (30,00 do preco_unitario * 1 da quantidade) **/

        // Taxa no parâmetro criado acima é de 8%
        // Conta: (30,00 * 0,08 = 2,40)
        // Taxa em vendas_lucro deve ser 2,40
        $this->assertEquals(2.40, $vendaLucro->taxa);

        // Revendedor recebe 10% de comissão do valor bruto de acordo com a parametrização de exemplo
        // Conta: (30,00 * 0,10 = 3,00)
        // Comissao para o revendedor em vendas_lucro deve ser 3,00
        $this->assertEquals(3.00, $vendaLucro->comissao);

        // Revendedor não tem indicador (ou data de validação expirou)
        // Conta: (30,00 * 0,00 = 0)
        // Comissao para indicador deve ser 0
        $this->assertEquals(0.00, $vendaLucro->comissao_indicado);

        // Outras despesas está como 11,50. O valor passado por parâmetro é inserido direto na tabela
        $this->assertEquals(11.50, $vendaLucro->outras_despesas);

        // Conta do Lucro Geral
        // Conta: 
        //        30,00 <- VALOR BRUTO
        //      -  2,40 <- TAXA PARAMETRIZADA
        //      -  3,00 <- COMISSAO REVENDEDOR
        //      -  0,00 <- COMISSAO INDICADOR DO REVENDEDOR (zero por que é inexistente ou validade da indicação expirou)
        //      - 11,50 <- OUTRAS DESPESAS
        //        _____ 
        //        13,10 <- LUCRO GERAL

        // Campo lucro geral deve ser igual a 13,10
        $this->assertEquals(13.10, $vendaLucro->lucro_geral);
    }

    /**
     * Testa inclusão de venda de um revendedor com indicação ainda na validade
     *
     * @return void
     */
    public function test_inclusao_venda_com_revendedor_com_indicacao_na_validade()
    {
        /**
         *  Neste teste o revendedor que vai fazer a venda tem um indicador com data de indicação válida.
         *  Ou seja, se um revendedor foi cadastrado na data de hoje, será consultado e retornado o valor inteiro do campo validade_comissao_indicado
         *   da tabela CONFIGURACOES e será efetuado uma soma do dia do cadastro mais o valor recuperado.
         *  Exemplo: 60 dias
         *           Data de cadastro do revendedor: 01/03/2022
         *           Data de validade para o indicador receber comissões: 30/04/2022
        **/

        $params = $this->getRevendedorParams();

        // Criar um revendedor que será o indicador
        $response = $this->postJson('/api/revendedor', $params);
        // Recuperar o id do indicador criado
        $idIndicador = $response['result']['id'];

        $response->assertCreated();

        // Criar outro revendedor com o campo indicador_id referenciando o id do revendedor acima
        $params = $this->getRevendedorParams();
        $params['indicador_id'] = $idIndicador;

        // Alterar alguns parâmetros para não retornar como duplicado
        $params['cpf'] = '97154580097';
        $params['celular'] = '(11) 88888-8888';

        // Criar o revendedor
        $response = $this->postJson('/api/revendedor', $params);
        // Recuperar id do revendedor com indicador
        $idRevendedor = $response['result']['id'];

        $response->assertCreated();
        $params = [
            'revendedor_id' => $idRevendedor,
            'taxa_parametro_id' => (TaxaParametro::create(TaxaParametro::factory()->make()->getAttributes()))->id, // taxa 8%
            // comissao revendedor 10% e indicador recebe 3%
            'comissao_parametro_id' => (ComissaoParametro::create(ComissaoParametro::factory()->make()->getAttributes()))->id, 
            'outras_despesas_valor' => 11.50,
            'descricao' => $this->faker->text(),
            'preco_unitario' => 30.00,
            'quantidade' => 1
        ];

        // Criar venda
        $response = $this->postJson('/api/venda', $params);
        $idVenda = $response['result']['id'];
        $response->assertStatus(201);

        // Verificar se tabela venda_lucros foi populada com calculos da venda criada
        $vendaLucro = VendaLucro::where('venda_id', $idVenda)->first();
        $this->assertNotEmpty($vendaLucro);

        /** Assumindo que o valor total da venda é 30,00 (30,00 do preco_unitario * 1 da quantidade) **/

        // Taxa no parâmetro criado acima é de 8%
        // Conta: (30,00 * 0,08 = 2,40)
        // Taxa em vendas_lucro deve ser 2,40
        $this->assertEquals(2.40, $vendaLucro->taxa);

        // Revendedor recebe 10% de comissão do valor bruto de acordo com a parametrização de exemplo
        // Conta: (30,00 * 0,10 = 3,00)
        // Comissao para o revendedor em vendas_lucro deve ser 3,00
        $this->assertEquals(3.00, $vendaLucro->comissao);

        // Revendedor POSSUI indicador data de validade ainda em andamento
        // Conta: (30,00 * 0,03 = 0,90)
        // Comissao para indicador deve ser 0,90
        $this->assertEquals(0.90, $vendaLucro->comissao_indicado);

        // Outras despesas está como 11,50. O valor passado por parâmetro é inserido direto na tabela
        $this->assertEquals(11.50, $vendaLucro->outras_despesas);

        // Conta do Lucro Geral
        // Conta: 
        //        30,00 <- VALOR BRUTO
        //      -  2,40 <- TAXA PARAMETRIZADA
        //      -  3,00 <- COMISSAO REVENDEDOR
        //      -  0,90 <- COMISSAO INDICADOR DO REVENDEDOR (indicação dentro da validade)
        //      - 11,50 <- OUTRAS DESPESAS
        //        _____ 
        //        12,20 <- LUCRO GERAL

        // Campo lucro geral deve ser igual a 12,20
        $this->assertEquals(12.20, $vendaLucro->lucro_geral);
    }

    public function test_inclusao_de_venda_deve_popular_tabela_comissoes()
    {
        $params = $this->getRevendedorParams();

        // Criar um revendedor e recuperar id
        $response = $this->postJson('/api/revendedor', $params);
        $idRevendedor = $response['result']['id'];

        $response->assertCreated();

        $params = [
            'revendedor_id' => $idRevendedor,
            'taxa_parametro_id' => (TaxaParametro::create(TaxaParametro::factory()->make()->getAttributes()))->id,
            'comissao_parametro_id' => (ComissaoParametro::create(ComissaoParametro::factory()->make()->getAttributes()))->id, 
            'outras_despesas_valor' => 11.50,
            'descricao' => $this->faker->text(),
            'preco_unitario' => 30.00,
            'quantidade' => 1
        ];

        // Criar venda
        $response = $this->postJson('/api/venda', $params);
        $idVenda = $response['result']['id'];
        $response->assertCreated();

        // Verificar se tabela venda_lucros foi populada com calculos da venda criada
        $vendaLucro = VendaLucro::where('venda_id', $idVenda)->first();
        $this->assertNotEmpty($vendaLucro);

        // Verificar se foi criado registro na tabela comissões referenciando o id do registro em vendas_lucro
        $this->assertNotEmpty(Comissao::find($vendaLucro->id));
    }


    public function getRevendedorParams()
    {
        return [
            'indicador_id' => '',
            'conta_pagamento_id' => '',
            'cpf' => '71245831011',
            'nome' => $this->faker->name(),
            'email' => $this->faker->email(),
            'celular' => '(11) 99999-9999',
            'ativo' => 1
        ];
    }
}
