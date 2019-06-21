<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class APIUserController extends Controller
{
    public function cadastrar(Request $rq)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|max:200',
            'email' => 'required|email|unique:users',
            'senha' => 'required|min:8'
        ])->validate();

        $token = Str::random(60);
        $user = User::create([
            'name' => $rq->nome,
            'email' => $rq->email,
            'password' => Hash::make($rq->senha),
            'api_token' => hash('sha256', $token)
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function gerarToken(Request $rq)
    {
        Validator::make($rq->all(), [
            'email' => 'required|email',
            'senha' => 'required'
        ])->validate();

        if (Auth::attempt(['email' => $rq->email, 'password' => $rq->senha]))
        {
            $token = Str::random(60);

            Auth::user()->fill([
                'api_token' => hash('sha256', $token)
            ])->save();

            return response()->json([
                'token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }
}
