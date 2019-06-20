<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Lista;

class APIListaController extends Controller
{
    use BuscarQuadroTrait;
    use BuscarTarefaTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function procurarLista(Request $rq, $id)
    {
        return $rq->user()->listas()->where('listas.id', $id)->first();
    }

    private function listaNotFound()
    {
        return response()->json([
            'message' => 'Lista não encontrada'
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
            'message' => 'Listas de atividades',
            'listas' => $q->listas
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

        $l = new Lista([
            'nome' => $rq->nome,
            'descricao' => $rq->descricao
        ]);
        $q->listas()->save($l);

        return response()->json([
            'message' => 'Lista criada com sucesso',
            'lista' => $l
        ], 200);
    }

    public function listarTarefas(Request $rq, $id)
    {
        $l = $this->procurarLista($rq, $id);
        if ($l == null)
        {
            return $this->listaNotFound();
        }

        return response()->json([
            'message' => 'Tarefas da lista',
            'tarefas' => $l->tarefas
        ], 200);
    }

    public function addTarefa(Request $rq, $id)
    {
        Validator::make($rq->all(), [
            'tarefa_id' => 'required|integer|unique:lista_tarefa'
        ], [
            'unique' => 'Essa tarefa já está em uma lista'
        ])->validate();

        $l = $this->procurarLista($rq, $id);
        if ($l == null)
        {
            return $this->listaNotFound();
        }

        if ($this->procurarTarefa($rq, $rq->tarefa_id) == null)
        {
            return $this->tarefaNotFound();
        }

        $l->tarefas()->attach($rq->tarefa_id);
        return response()->json([
            'message' => 'Tarefa adicionada com sucesso'
        ], 200);
    }
}
