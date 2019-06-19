<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quadro;
use Illuminate\Support\Facades\Validator;

class APIQuadroController extends Controller
{
    use BuscarQuadroTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function listar(Request $rq)
    {
        $u = $rq->user();
        return response()->json([
            'message' => 'Lista de quadros',
            'quadros' => $u->quadros
        ], 200);
    }

    public function criar(Request $rq)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string'
        ])->validate();

        $q = new Quadro([
            'nome' => $rq->nome,
            'descricao' => $rq->descricao
        ]);
        $rq->user()->quadros()->save($q);

        return response()->json([
            'message' => 'Quadro criado com sucesso',
            'quadro' => $q
        ], 200);
    }

    public function atualizar(Request $rq, $id)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string'
        ])->validate();

        $q = $this->procurarQuadro($rq, $id);
        if ($q == null)
        {
            return $this->quadroNotFound();
        }

        $q->fill([
            'nome' => $rq->nome,
            'descricao' => $rq->descricao
        ])->save();
        return response()->json([
            'message' => 'Quadro atualizado com sucesso',
            'quadro' => $q
        ], 200);
    }

    public function deletar(Request $rq, $id)
    {
        $q = $this->procurarQuadro($rq, $id);
        if ($q == null)
        {
            return $this->quadroNotFound();
        }

        $q->delete();
        return response()->json([
            'message' => 'Quadro deletado com sucesso'
        ], 200);
    }
}
