<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revendedores', function (Blueprint $table) {
            $table->bigInteger('id')->unique();
            $table->string('rg')->unique()->nullable();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('celular')->nullable();
            $table->boolean('ativo')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revendedores');
    }
}
