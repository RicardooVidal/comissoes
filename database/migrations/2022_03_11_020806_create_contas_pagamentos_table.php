<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revendedor_id');
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->integer('agencia')->nullable();
            $table->integer('digito_agencia')->nullable();
            $table->integer('conta')->nullable();
            $table->integer('digito_conta')->nullable();
            $table->string('tipo')->nullable();
            $table->string('pix')->nullable();

            //foreign key (constraints)
            $table->foreign('revendedor_id')->references('id')->on('revendedores')->onDelete('cascade');
            $table->foreign('banco_id')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_pagamentos', function(Blueprint $table) {
            $table->dropForeign('contas_pagamentos_revendedor_id_foreign');
            $table->dropForeign('contas_pagamentos_banco_id_foreign');
            $table->drop();
        });
    }
}
