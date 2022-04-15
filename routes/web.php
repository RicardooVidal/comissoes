<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('/comissoes')->group(function () {
        Route::get('/', function() {
            return view('comissoes.index');
        })->name('comissoes.index');
    });

    Route::prefix('/vendas')->group(function () {
        Route::get('/', function() {
            return view('vendas.index');
        })->name('vendas.index');

        Route::get('/create', function() {
            return view('vendas.create');
        })->name('vendas.create');
    });

    Route::prefix('/revendedores')->group(function () {
        Route::get('/', function() {
            return view('revendedores.index');
        })->name('revendedores.index');

        Route::get('/create', function() {
            return view('revendedores.create');
        })->name('revendedores.create');
    });

    Route::prefix('/parametros')->group(function () {
        Route::get('/taxas_parametros', function() {
            return view('parametros.taxas.index');
        })->name('parametros.taxas.index');
    
        Route::get('/comissoes_parametros', function() {
            return view('parametros.comissoes.index');
        })->name('parametros.comissoes.index');
    });

    Route::prefix('/outros')->group(function () {
        Route::get('/configuracoes', function() {
            return view('outros.configuracoes.index');
        })->name('outros.configuracoes.index');

    });
});