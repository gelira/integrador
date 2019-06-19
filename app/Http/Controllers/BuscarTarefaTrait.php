<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait BuscarTarefaTrait
{
    private function procurarTarefa(Request $rq, $id)
    {
        return $rq->user()->tarefas()->where('tarefas.id', $id)->first();
    }

    private function tarefaNotFound()
    {
        return response()->json([
            'message' => 'Tarefa não encontrado'
        ], 404);
    }
}
