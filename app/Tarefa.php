<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use SoftDeletes;

    protected $fillable = ['nome', 'descricao'];

    public function quadro()
    {
        return $this->belongsTo('App\Quadro');
    }

    public function listas()
    {
        return $this->belongsToMany('App\Lista')
            ->using('App\ListaTarefa')
            ->withPivot(['created_at']);
    }
}
