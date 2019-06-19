<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lista extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'minutos_pomodoro',
        'short_timebreak',
        'long_timebreak'
    ];

    public function quadro()
    {
        return $this->belongsTo('App\Quadro');
    }

    public function tarefas()
    {
        return $this->belongsToMany('App\Tarefa')
            ->using('App\ListaTarefa')
            ->withPivot(['created_at']);
    }
}
