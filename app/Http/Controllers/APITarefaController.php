<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Tarefa;
use Illuminate\Validation\Rule;

class APITarefaController extends Controller
{
    use ModelNotFoundTrait;

    private $modelName = 'Tarefa';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function getQuerySet(Request $rq)
    {
        return $rq->user()->tarefas();
    }

    public function getTarefa(Request $rq, $id)
    {
        return response()->json([
            'tarefa' => $this->getModelDB($rq, $id, 'tarefas.id')
        ], 200);
    }

    public function criar(Request $rq)
    {
        Validator::make($rq->all(), [
            'quadro_id' => 'required|integer',
            'nome' => 'required|string|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $quadro = $this->getModelDB($rq, $rq->quadro_id,
            'id', $rq->user()->quadros(), 'Quadro');
        $tarefa = new Tarefa($rq->only(['nome', 'descricao']));
        $quadro->tarefas()->save($tarefa);
        $rq->user()->registrarLog('Criada a tarefa ' . $tarefa->nome . ' - id ' . $tarefa->id .
            ' no quadro ' . $quadro->nome . ' - id ' . $quadro->id);

        return response()->json([
            'message' => 'Tarefa criada com sucesso',
            'tarefa' => $tarefa
        ], 200);
    }

    public function editar(Request $rq, $id)
    {
        $tarefa = $this->getModelDB($rq, $id, 'tarefas.id');

        Validator::make($rq->all(), [
            'nome' => 'required|string|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $tarefa->fill($rq->only(['nome', 'descricao']))->save();
        $rq->user()->registrarLog('Editada a tarefa ' . $tarefa->nome . ' - id ' . $tarefa->id);

        return response()->json([
            'message' => 'Tarefa editada com sucesso',
            'tarefa' => $tarefa
        ], 200);
    }

    public function anotacoes(Request $rq, $id)
    {
        $tarefa = $this->getModelDB($rq, $id, 'tarefas.id');

        Validator::make($rq->all(), [
            'anotacoes' => 'nullable|string'
        ])->validate();

        $tarefa->forceFill($rq->only('anotacoes'))->save();
        $rq->user()->registrarLog('Anotações na tarefa ' . $tarefa->nome . ' - id ' . $tarefa->id);

        return response()->json([
            'message' => 'Anotação inserida com sucesso',
            'tarefa' => $tarefa
        ], 200);
    }

    public function status(Request $rq, $id)
    {
        $tarefa = $this->getModelDB($rq, $id, 'tarefas.id');

        Validator::make($rq->all(), [
            'status' => [
                'required',
                Rule::in(['Por fazer', 'Fazendo', 'Feito'])
            ]
        ])->validate();

        $tarefa->forceFill($rq->only('status'))->save();
        $rq->user()->registrarLog('Status da tarefa ' . $tarefa->nome . ' - id ' . $tarefa->id);

        return response()->json([
            'message' => 'Status atualizado com sucesso',
            'tarefa' => $tarefa
        ], 200);
    }

    public function deletar(Request $rq, $id)
    {
        $tarefa = $this->getModelDB($rq, $id, 'tarefas.id');
        $tarefa->listas()->detach();
        $tarefa->delete();
        $rq->user()->registrarLog('Deletada a tarefa ' . $tarefa->nome . ' - id ' . $tarefa->id);

        return response()->json([
            'message' => 'Tarefa deletada com sucesso'
        ], 200);
    }
}
