<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revendedor_id');
            $table->unsignedBigInteger('taxa_parametro_id');
            $table->unsignedBigInteger('comissao_parametro_id');
            $table->decimal('outras_despesas_valor')->nullable();
            $table->string('outras_despesas_descricao')->nullable();
            $table->string('descricao');
            $table->decimal('preco_unitario');
            $table->integer('quantidade');
            $table->decimal('preco_total');
            $table->string('link_venda')->nullable();
            $table->date('data_venda');

            //foreign key (constraints)
            $table->foreign('revendedor_id')->references('id')->on('revendedores')->onDelete('cascade');
            $table->foreign('taxa_parametro_id')->references('id')->on('taxas_parametros');
            $table->foreign('comissao_parametro_id')->references('id')->on('comissoes_parametros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendas', function(Blueprint $table) {
            $table->dropForeign('vendas_revendedor_id_foreign');
            $table->dropForeign('vendas_taxa_parametro_id_foreign');
            $table->dropForeign('vendas_comissao_parametro_id_foreign');
            $table->drop();
        });
    }
}
