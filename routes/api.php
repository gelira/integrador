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
    Route::get('all', 'APIQuadroController@all');
    Route::get('{id}', 'APIQuadroController@getQuadro');
    Route::get('{id}/tarefas', 'APIQuadroController@getTarefas');
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
    Route::get('{id}', 'APIListaController@getLista');
    Route::post('', 'APIListaController@criar');
    Route::put('{id}', 'APIListaController@editar');
    Route::put('{id}/pomodoro', 'APIListaController@tempoPomodoro');
    Route::post('{id}/add-tarefa', 'APIListaController@addTarefa');
    Route::delete('{id}/rm-tarefa/{tarefa_id}', 'APIListaController@rmTarefa');
    Route::delete('{id}', 'APIListaController@deletar');
    /*Route::get('listar/{quadro_id}', 'APIListaController@listar');
    Route::post('criar/{quadro_id}', 'APIListaController@criar');
    Route::get('tarefas/{id}', 'APIListaController@listarTarefas');
    Route::post('add-tarefa/{id}', 'APIListaController@addTarefa');
    Route::post('rm-tarefa/{id}', 'APIListaController@rmTarefa');*/
});
