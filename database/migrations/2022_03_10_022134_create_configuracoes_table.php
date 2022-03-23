<?php

use App\Models\Configuracao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->integer('validade_comissao_indicado');
            $table->boolean('recuperar_descricao_compra');
            $table->timestamps();
        });

        Configuracao::create([
            'validade_comissao_indicado' => 60,
            'recuperar_descricao_compra' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracoes');
    }
}
