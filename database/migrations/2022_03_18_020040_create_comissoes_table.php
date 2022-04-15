<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComissoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revendedor_id');
            $table->unsignedBigInteger('vendas_lucros_id');
            $table->unsignedBigInteger('venda_id');
            $table->unsignedBigInteger('forma_pagamento_id')->nullable();
            $table->string('descricao');
            $table->boolean('pago')->default(false);
            $table->date('data_gerado');
            $table->date('data_pagamento')->nullable();
            
            //foreign key (constraints)
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('revendedor_id')->references('id')->on('revendedores')->onDelete('cascade');
            $table->foreign('vendas_lucros_id')->references('id')->on('vendas_lucros')->onDelete('cascade');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comissoes', function(Blueprint $table) {
            $table->dropForeign('comissoes_forma_pagamento_id_foreign');
            $table->dropForeign('comissoes_vendas_lucros_id_foreign');
            $table->drop();
        });
    }
}
