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
    Route::get('', 'APIUserController@getDados');
    Route::post('cadastrar', 'APIUserController@cadastrar');
    Route::post('gerar-token', 'APIUserController@gerarToken');
    Route::put('nova-senha', 'APIUserController@novaSenha');
    Route::post('foto', 'APIUserController@atualizarFoto');
    Route::delete('foto', 'APIUserController@deletarFoto');
    Route::get('log/{limit?}', 'APIUserController@getLog')->where('limit', '[0-9]+');
});

Route::prefix('quadro')->group(function() {
    Route::get('all', 'APIQuadroController@all');
    Route::get('{id}', 'APIQuadroController@getQuadro');
    Route::get('{id}/tarefas', 'APIQuadroController@getTarefas');
    Route::get('{id}/tarefas-sem-lista', 'APIQuadroController@getTarefasSemLista');
    Route::get('{id}/listas', 'APIQuadroController@getListas');
    Route::post('', 'APIQuadroController@criar');
    Route::put('{id}', 'APIQuadroController@editar');
    Route::delete('{id}', 'APIQuadroController@deletar');
});

Route::prefix('tarefa')->group(function () {
    Route::get('{id}', 'APITarefaController@getTarefa');
    Route::post('', 'APITarefaController@criar');
    Route::put('{id}', 'APITarefaController@editar');
    Route::patch('{id}/anotacoes', 'APITarefaController@anotacoes');
    Route::patch('{id}/status', 'APITarefaController@status');
    Route::delete('{id}', 'APITarefaController@deletar');
});

Route::prefix('lista')->group(function () {
    Route::get('all', 'ApiListaController@all');
    Route::get('{id}', 'APIListaController@getLista');
    Route::post('', 'APIListaController@criar');
    Route::put('{id}', 'APIListaController@editar');
    Route::put('{id}/pomodoro', 'APIListaController@tempoPomodoro');
    Route::get('{id}/tarefas', 'APIListaController@getTarefas');
    Route::post('{id}/add-tarefa', 'APIListaController@addTarefa');
    Route::delete('{id}/rm-tarefa/{tarefa_id}', 'APIListaController@rmTarefa');
    Route::delete('{id}', 'APIListaController@deletar');
});
