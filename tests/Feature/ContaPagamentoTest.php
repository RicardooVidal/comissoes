<?php

namespace Tests\Feature;

use App\Models\Banco;
use App\Models\Revendedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ContaPagamentoTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;

    protected $postParams = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->setParams();
    }

    /**
     * Verificar se revendedor existe no momento da inclusão
     */
    public function test_na_inclusao_verificar_se_revendedor_existe()
    {
        $params = $this->postParams;

        //Revendedor inexistente
        $params['revendedor_id'] = '999';

        // Incluir revendedor
        $this->postJson('/api/revendedor', $params)
            ->assertUnprocessable();
    }

    /**
     * Verificar se na inclusão atualiza o id contas pagamento do revendedor
     *
     * @return void
     */
    public function test_na_inclusao_verificar_se_id_conta_pagamento_atualiza_no_revendedor()
    {
        $params = $this->postParams;

        // Incluir revendedor
        $response = $this->postJson('/api/revendedor', [
            'indicador_id' => '',
            'conta_pagamento_id' => '',
            'cpf' => '71245831011',
            'nome' => $this->faker->name(),
            'email' => $this->faker->email(),
            'celular' => '(11) 99999-9999',
            'ativo' => 1
        ]);
        
        //Recuperar o id
        $id = $response['result']['id'];

        $response->assertCreated();

        $params = $this->postParams;
        $params['banco_id'] = Banco::create(['id' => '999', 'descricao' => $this->faker->title()])->id;
        $params['revendedor_id'] = $id;

        $response = $this->postJson('/api/conta_pagamento', $params);

        //Recuperar o id
        $idConta = $response['result']['id'];
            
        $response->assertCreated();

        // Verificar no registro do revendedor se o id de contas pagamento foi inserido
        $revendedor = Revendedor::find($id);

        $this->assertEquals($revendedor->conta_pagamento_id, $idConta);
    }

    public function test_na_atualizacao_nao_deixar_alterar_revendedor()
    {
        // Incluir revendedor
        $response = $this->postJson('/api/revendedor', [
            'indicador_id' => '',
            'conta_pagamento_id' => '',
            'cpf' => '71245831011',
            'nome' => $this->faker->name(),
            'email' => $this->faker->email(),
            'celular' => '(11) 99999-9999',
            'ativo' => 1
        ]);

        //Recuperar o id
        $id = $response['result']['id'];

        $response->assertCreated();

        $params = $this->postParams;
        $params['banco_id'] = Banco::create(['id' => '999', 'descricao' => $this->faker->title()])->id;
        $params['revendedor_id'] = $id;

        $response = $this->postJson('/api/conta_pagamento', $params);

        //Recuperar o id
        $idConta = $response['result']['id'];

        $this->putJson("/api/conta_pagamento/$idConta", $params)
            ->assertJson(['result' => 'Não é possível alterar o revendedor']);

    }

    public function setParams()
    {
        $this->postParams = [
            'revendedor_id' => '',
            'banco_id' => '',
            'agencia' => $this->faker->randomNumber(3),
            'digito_agencia' => $this->faker->randomDigit(),
            'conta' => $this->faker->randomNumber(5),
            'digito_conta' => $this->faker->randomDigit(),
            'tipo' => 'CORRENTE',
            'pix' => $this->faker->email()
        ];
    }
}
