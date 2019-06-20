<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ModelNotFoundTrait
{
    private function getModelDB(Request $rq, $id, $idName = 'id', $qs = null, $model = null)
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
            if ($model == null)
            {
                $model = $this->modelName;
            }
            $ex->setModel($model);
            throw $ex;
        }
    }
}
