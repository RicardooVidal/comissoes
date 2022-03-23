<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComissoesParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissoes_parametros', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('comissao_percentual')->nullable();
            $table->decimal('comissao_indicado_percentual')->nullable();
            $table->boolean('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('comissoes_parametros');
    }
}
