<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait BuscarQuadroTrait
{
    private function procurarQuadro(Request $rq, $id)
    {
        return $rq->user()->quadros()->where('id', $id)->first();
    }

    private function quadroNotFound()
    {
        return response()->json([
            'message' => 'Quadro não encontrado'
        ], 404);
    }
}
