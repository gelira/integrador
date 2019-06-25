<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quadro;
use Illuminate\Support\Facades\Validator;

class APIQuadroController extends Controller
{
    use ModelNotFoundTrait;

    private $modelName = 'Quadro';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function getQuerySet(Request $rq)
    {
        return $rq->user()->quadros();
    }

    private function validacaoPadrao($dados)
    {
        Validator::make($dados, [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string'
        ])->validate();
    }

    public function all(Request $rq)
    {
        return response()->json([
            'quadros' => $rq->user()->quadros
        ], 200);
    }

    public function getQuadro(Request $rq, $id)
    {
        return response()->json([
            'quadro' => $this->getModelDB($rq, $id)
        ], 200);
    }

    public function criar(Request $rq)
    {
        $this->validacaoPadrao($rq->all());

        $user = $rq->user();
        $quadro = new Quadro($rq->only(['nome', 'descricao']));
        $user->quadros()->save($quadro);
        $user->registrarLog('Criado o quadro ' . $quadro->nome . ' - id ' . $quadro->id);

        return response()->json([
            'message' => 'Quadro criado com sucesso',
            'quadro' => $quadro
        ], 200);
    }

    public function editar(Request $rq, $id)
    {
        $quadro = $this->getModelDB($rq, $id);

        $this->validacaoPadrao($rq->all());

        $quadro->fill($rq->only(['nome', 'descricao']))->save();
        $rq->user()->registrarLog('Editado o quadro ' . $quadro->nome . ' - id ' . $quadro->id);

        return response()->json([
            'message' => 'Quadro editado com sucesso',
            'quadro' => $quadro
        ], 200);
    }

    public function deletar(Request $rq, $id)
    {
        $quadro = $this->getModelDB($rq, $id);
        $quadro->delete();
        $rq->user()->registrarLog('Deletado o quadro ' . $quadro->nome . ' - id ' . $quadro->id);

        return response()->json([
            'message' => 'Quadro deletado com sucesso'
        ], 200);
    }

    public function getTarefas(Request $rq, $id)
    {
        return response()->json([
            'tarefas' => $this->getModelDB($rq, $id)->tarefas
        ], 200);
    }

    public function getListas(Request $rq, $id)
    {
        return response()->json([
            'listas' => $this->getModelDB($rq, $id)->listas
        ], 200);
    }

    public function getTarefasSemLista(Request $rq, $id)
    {
        return response()->json([
            'tarefas' => $this->getModelDB($rq, $id)->tarefas()
                ->whereDoesntHave('listas')->get()
        ], 200);
    }
}
