<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwt.auth')->group(function() {
    ## Comissão de revendedor por venda
    Route::prefix('/comissao')->group(function() {
        Route::get('/', 'App\Http\Controllers\ComissaoController@index');
        Route::get('/getComissoesByVenda', 'App\Http\Controllers\ComissaoController@getComissoesByVenda');
        Route::get('/getAllComissoesByRevendedor', 'App\Http\Controllers\ComissaoController@getAllComissoesByRevendedor');
        Route::post('/baixar', 'App\Http\Controllers\ComissaoController@baixar');
        Route::post('/estornar', 'App\Http\Controllers\ComissaoController@estornar');
    });

    ## Vendas
    Route::apiResource('venda', 'App\Http\Controllers\VendaController');

    ## Revendedores
    Route::apiResource('revendedor', 'App\Http\Controllers\RevendedorController');
    Route::get('revendedor/{id}/indicados', 'App\Http\Controllers\RevendedorController@getRevendedoresIndicados');
    Route::get('revendedor/{id}/dados_bancarios', 'App\Http\Controllers\RevendedorController@getDadosBancarios');

    ## Conta Pagamento
    Route::apiResource('/conta_pagamentos', 'App\Http\Controllers\ContaPagamentoController');

    ## Indicadores
    Route::apiResource('indicador', 'App\Http\Controllers\IndicadorController');

    ## Parâmetros
    Route::prefix('/parametros')->group(function() {
        ## Bancos
        Route::apiResource('bancos', 'App\Http\Controllers\BancoController');

        ## Forma Pagamento
        Route::apiResource('/formas_pagamentos', 'App\Http\Controllers\FormaPagamentoController');

       ## Taxas Parâmetro
       Route::apiResource('/taxas_parametros', 'App\Http\Controllers\TaxaParametroController');

       ## Comissões Parâmetro
       Route::apiResource('/comissoes_parametros', 'App\Http\Controllers\ComissaoParametroController');
    });

    ## Configuracoes
    Route::prefix('/configuracoes')->group(function() {
        Route::get('/', 'App\Http\Controllers\ConfiguracaoController@index');
        Route::put('/', 'App\Http\Controllers\ConfiguracaoController@update');
    });
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::get('me', 'App\Http\Controllers\AuthController@me');
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');