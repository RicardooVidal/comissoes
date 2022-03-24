<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revendedor_id');

            //foreign key (constraints)
            $table->foreign('revendedor_id')->references('id')->on('revendedores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicadores', function(Blueprint $table) {
            $table->dropForeign('indicadores_revendedor_id_foreign');
            $table->drop();
        });
    }
}
