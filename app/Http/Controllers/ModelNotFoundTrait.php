<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFoundTrait
{
    private function getModelDB(Request $rq, $id, $idName = 'id', $qs = null)
    {
        try
        {
            if ($qs == null)
            {
                $qs = $this->getQuerySet($rq);
            }
            return $qs->where($idName, $id)->firstOrFail();
        }

        catch (ModelNotFoundException $ex)
        {
            $ex->setModel($this->modelName);
            throw $ex;
        }
    }
}
