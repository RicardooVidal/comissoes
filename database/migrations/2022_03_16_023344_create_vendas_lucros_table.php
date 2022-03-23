<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasLucrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas_lucros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venda_id');
            $table->decimal('taxa')->nullable();
            $table->decimal('comissao')->nullable();
            $table->decimal('comissao_indicado')->nullable();
            $table->decimal('outras_despesas')->nullable();
            $table->decimal('lucro_geral');

            //foreign key (constraints)
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendas_lucros', function(Blueprint $table) {
            $table->dropForeign('vendas_lucros_venda_id_foreign');
            $table->drop();
        });
    }
}
