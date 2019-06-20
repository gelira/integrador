<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    Route::post('cadastrar', 'APIUserController@cadastrar');
    Route::post('gerar-token', 'APIUserController@gerarToken');
});

Route::prefix('quadro')->group(function() {
    Route::get('listar', 'APIQuadroController@listar');
    Route::post('criar', 'APIQuadroController@criar');
    Route::post('atualizar/{id}', 'APIQuadroController@atualizar');
    Route::get('deletar/{id}', 'APIQuadroController@deletar');
});

Route::prefix('tarefa')->group(function () {
    Route::get('listar/{quadro_id}', 'APITarefaController@listar');
    Route::post('criar/{quadro_id}', 'APITarefaController@criar');
    Route::post('atualizar/{id}', 'APITarefaController@atualizar');
    Route::post('anotacoes/{id}', 'APITarefaController@anotacoes');
    Route::post('status/{id}', 'APITarefaController@status');
    Route::get('deletar/{id}', 'APITarefaController@deletar');
});

Route::prefix('lista')->group(function () {
    Route::get('listar/{quadro_id}', 'APIListaController@listar');
    Route::post('criar/{quadro_id}', 'APIListaController@criar');
    Route::get('tarefas/{id}', 'APIListaController@listarTarefas');
    Route::post('add-tarefa/{id}', 'APIListaController@addTarefa');
    Route::post('rm-tarefa/{id}', 'APIListaController@rmTarefa');
});
