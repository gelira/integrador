<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quadro extends Model
{
    use SoftDeletes;

    protected $fillable = ['nome', 'descricao'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function listas()
    {
        return $this->hasMany('App\Lista');
    }

    public function tarefas()
    {
        return $this->hasMany('App\Tarefa');
    }
}
