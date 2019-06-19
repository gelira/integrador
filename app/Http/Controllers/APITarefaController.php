<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Tarefa;
use Illuminate\Validation\Rule;

class APITarefaController extends Controller
{
    use BuscarQuadroTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function procurarTarefa(Request $rq, $id)
    {
        return $rq->user()->tarefas()->where('tarefas.id', $id)->first();
    }

    private function tarefaNotFound()
    {
        return response()->json([
            'message' => 'Tarefa não encontrada'
        ], 404);
    }

    public function listar(Request $rq, $quadro_id)
    {
        $q = $this->procurarQuadro($rq, $quadro_id);
        if ($q == null)
        {
            return $this->quadroNotFound();
        }

        return response()->json([
            'message' => 'Lista de tarefas',
            'tarefas' => $q->tarefas
        ], 200);
    }

    public function criar(Request $rq, $quadro_id)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $q = $this->procurarQuadro($rq, $quadro_id);
        if ($q == null)
        {
            return $this->quadroNotFound();
        }

        $t = new Tarefa([
            'nome' => $rq->nome,
            'descricao' => $rq->descricao
        ]);
        $q->tarefas()->save($t);

        return response()->json([
            'message' => 'Tarefa criada com sucesso',
            'tarefa' => $t
        ], 200);
    }

    public function atualizar(Request $rq, $id)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|max:50',
            'descricao' => 'nullable|string'
        ])->validate();

        $t = $this->procurarTarefa($rq, $id);
        if ($t == null)
        {
            return $this->tarefaNotFound();
        }

        $t->fill([
            'nome' => $rq->nome,
            'descricao' => $rq->descricao
        ])->save();
        return response()->json([
            'message' => 'Tarefa atualizada com sucesso',
            'tarefa' => $t
        ], 200);
    }

    public function anotacoes(Request $rq, $id)
    {
        Validator::make($rq->all(), [
            'anotacoes' => 'nullable|string'
        ])->validate();

        $t = $this->procurarTarefa($rq, $id);
        if ($t == null)
        {
            return $this->tarefaNotFound();
        }

        $t->anotacoes = $rq->anotacoes;
        $t->save();
        return response()->json([
            'message' => 'Anotação inserida com sucesso',
            'tarefa' => $t
        ], 200);
    }

    public function status(Request $rq, $id)
    {
        Validator::make($rq->all(), [
            'status' => [
                'required',
                Rule::in(['Por fazer', 'Fazendo', 'Feito'])
            ]
        ])->validate();

        $t = $this->procurarTarefa($rq, $id);
        if ($t == null)
        {
            return $this->tarefaNotFound();
        }

        $t->status = $rq->status;
        $t->save();
        return response()->json([
            'message' => 'Status atualizado com sucesso',
            'tarefa' => $t
        ], 200);
    }
}
