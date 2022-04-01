<?php

namespace Tests\Feature;

use App\Models\Indicador;
use App\Models\Revendedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class RevendedorTest extends TestCase
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
     * CPF inválido retorna 422
     *
     * @return void
     */
    public function test_cpf_invalido_retorna_422()
    {
        $params = $this->postParams;

        //CPF Inválido
        $params['id'] = '44072829839';
 
        $this->postJson('/api/revendedor', $params)
            ->assertUnprocessable();
    }

   /**
     * Inclusão de revendedor com indicador desativado
     *
     * @return void
     */
    public function test_inclusao_revendedor_com_indicador_desativado()
    {
        $params = $this->postParams;

        // Desativando revendedor
        $params['ativo'] = 0;

        $response = $this->postJson('/api/revendedor', $params);
        $id = $response['result']['id'];
        
        $response->assertCreated();

        $this->setParams();

        $params = $this->postParams;

        $params['id'] = '65405788066';
        $params['indicador_id'] = $id;
        $params['celular'] = '(11) 88888-8888';
        $params['ativo'] = 1;

        $this->postJson('/api/revendedor', $params)
            ->assertJson([
                'result' => 'Revendedor indicador desativado'
            ]);
    }

   /**
     * Inclusão de revendedor com indicador inexistente
     *
     * @return void
     */
    public function test_inclusao_revendedor_com_indicador_inexistente()
    {
        $params = $this->postParams;

        // Indicador inexistente
        $params['indicador_id'] = 999;

        $this->postJson('/api/revendedor', $params)
            ->assertNotFound();
    }

   /**
     * Atualização de revendedor não permite atualizar o indicador
     *
     * @return void
     */
    public function test_atualizacao_revendedor_nao_permite_atualizar_indicador()
    {
        $params = $this->postParams;

        $response = $this->postJson('/api/revendedor', $params);
        $id = $response['result']['id'];

        $response->assertCreated();

        $this->setParams();

        $params = $this->postParams;

        // Tentando atualizar o indicador
        $params['id'] = '22169006052';
        $params['indicador_id'] = 999;

        $this->putJson("/api/revendedor/$id", $params)
            ->assertJson([
                'result' => 'Não é possível alterar o indicador'
            ]);
    }

   /**
     * Atualização de revendedor com indicador inexistente
     *
     * @return void
     */
    public function test_atualizacao_revendedor_nao_permite_atualizar_id_contas_pagamento()
    {
        $params = $this->postParams;

        $response = $this->postJson('/api/revendedor', $params);
        $id = $response['result']['id'];

        $response->assertCreated();

        $this->setParams();

        $params = $this->postParams;

        // Tentando atualizar o indicador
        $params['id'] = '22169006052';
        $params['conta_pagamento_id'] = 999;

        $this->putJson("/api/revendedor/$id", $params)
            ->assertJson([
                'result' => 'Não é possível alterar o id das informações de pagamento'
            ]);
    }

   /**
     * Inclusão de revendedor com indicador deve popular tabela indicadores
     *
     * @return void
     */
    public function test_inclusao_revendedor_com_indicador_deve_popular_tabela_indicadores()
    {
        $params = $this->postParams;

        //Inserir revendedor (que será o indicador)
        $response = $this->postJson('/api/revendedor', $params);

        //Recuperar o id
        $id = $response['result']['id'];

        $response->assertCreated();

        // Renovar parâmetros
        $this->setParams();

        $params = $this->postParams;

        // Inserir outro revendedor com o parâmetro indicador_id com o id do revendedor acima
        $params['id'] = '65811329008';
        $params['celular'] = '(11) 88888-8888';
        $params['indicador_id'] = $id;

        // Inserir o revendedor indicado
        $response = $this->postJson('/api/revendedor', $params);
        
        // Procurar o id do indicador na tabela indicadores para ver se foi inserido
        $indicador = Indicador::all();

        $this->assertNotEmpty($indicador);
    }

    public function setParams()
    {
        $this->postParams = [
            'indicador_id' => '',
            'conta_pagamento_id' => '',
            'id' => '44072829838',
            'nome' => $this->faker->name(),
            'email' => $this->faker->email(),
            'celular' => '(11) 99999-9999',
            'ativo' => 1
        ];
    }
}
