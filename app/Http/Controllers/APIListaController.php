<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Lista;

class APIListaController extends Controller
{
    use BuscarQuadroTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
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
}
