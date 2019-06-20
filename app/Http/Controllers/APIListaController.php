<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Lista;

class APIListaController extends Controller
{
    use ModelNotFoundTrait;

    private $modelName = 'Lista';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function getQuerySet(Request $rq)
    {
        return $rq->user()->listas();
    }

    public function getLista(Request $rq, $id)
    {
        return response()->json([
            'lista' => $this->getModelDB($rq, $id, 'listas.id')
        ], 200);
    }

    public function criar(Request $rq)
    {
        Validator::make($rq->all(), [
            'quadro_id' => 'required|integer',
            'nome' => 'required|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $quadro = $this->getModelDB($rq, $rq->quadro_id,
            'id', $rq->user()->quadros(), 'Quadro');
        $lista = new Lista($rq->only(['nome', 'descricao']));
        $quadro->listas()->save($lista);

        return response()->json([
            'message' => 'Lista criada com sucesso',
            'lista' => $lista->refresh()
        ], 200);
    }

    public function editar(Request $rq, $id)
    {
        $lista = $this->getModelDB($rq, $id, 'listas.id');

        Validator::make($rq->all(), [
            'nome' => 'required|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $lista->fill($rq->only(['nome', 'descricao']))->save();
        return response()->json([
            'message' => 'Lista editada com sucesso',
            'lista' => $lista
        ], 200);
    }

    public function tempoPomodoro(Request $rq, $id)
    {
        $lista = $this->getModelDB($rq, $id, 'listas.id');

        Validator::make($rq->all(), [
            'minutos_pomodoro' => 'required|integer|min:1',
            'short_timebreak' => 'required|integer|min:1',
            'long_timebreak' => 'required|integer|min:1'
        ])->validate();

        $lista->forceFill($rq->only([
            'minutos_pomodoro', 'short_timebreak', 'long_timebreak'
        ]))->save();
        return response()->json([
            'message' => 'Tempo definido com sucesso',
            'lista' => $lista
        ], 200);
    }

    public function addTarefa(Request $rq, $id)
    {
        $lista = $this->getModelDB($rq, $id, 'listas.id');

        Validator::make($rq->all(), [
            'tarefa_id' => 'required|integer|unique:lista_tarefa'
        ], [
            'unique' => 'Essa tarefa já está em uma lista'
        ])->validate();

        $t_id = $rq->tarefa_id;
        $tarefa = $this->getModelDB($rq, $t_id,
            'tarefas.id', $rq->user()->tarefas(), 'Tarefa');
        $lista->tarefas()->attach($t_id);

        return response()->json([
            'message' => 'Tarefa adicionada com sucesso',
            'tarefa' => $tarefa
        ], 200);
    }

    public function rmTarefa(Request $rq, $id, $tarefa_id)
    {
        return response()->json([
            'linhas_afetadas' => $this->getModelDB($rq, $id, 'listas.id')
                ->tarefas()->detach($tarefa_id)
        ], 200);
    }

    public function deletar(Request $rq, $id)
    {
        $lista = $this->getModelDB($rq, $id, 'listas.id');
        $lista->tarefas()->detach();
        $lista->delete();
        return response()->json([
            'message' => 'Lista deletada com sucesso'
        ], 200);
    }
}
