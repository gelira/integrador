<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function quadros()
    {
        return $this->hasMany('App\Quadro');
    }

    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    public function tarefas()
    {
        return $this->hasManyThrough('App\Tarefa', 'App\Quadro');
    }

    public function listas()
    {
        return $this->hasManyThrough('App\Lista', 'App\Quadro');
    }
}
