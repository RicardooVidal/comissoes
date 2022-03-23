<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConstraintRevendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revendedores', function (Blueprint $table) {
            $table->unsignedBigInteger('indicador_id')->nullable();
            $table->unsignedBigInteger('conta_pagamento_id')->nullable();
            $table->date('data_indicacao')->nullable();
            $table->date('validade_indicacao')->nullable();

            //foreign key (constraints)
            $table->foreign('indicador_id')->references('id')->on('indicadores')->onDelete('cascade');
            $table->foreign('conta_pagamento_id')->references('id')->on('contas_pagamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revendedores', function (Blueprint $table) {
            $table->dropForeign('revendedores_conta_pagamento_id_foreign');
            $table->dropForeign('revendedores_indicador_id_foreign');
            $table->dropColumn('validade_indicacao');
            $table->dropColumn('data_indicacao');
            $table->dropColumn('conta_pagamento_id');
            $table->dropColumn('indicador_id');
            
            
        });
    }
}
