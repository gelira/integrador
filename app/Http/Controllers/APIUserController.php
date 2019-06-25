<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class APIUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('cadastrar', 'gerarToken');
    }

    public function cadastrar(Request $rq)
    {
        Validator::make($rq->all(), [
            'nome' => 'required|string|max:200',
            'email' => 'required|email|unique:users',
            'senha' => 'required|string|min:8'
        ])->validate();

        $token = Str::random(60);
        $user = User::create([
            'name' => $rq->nome,
            'email' => $rq->email,
            'password' => Hash::make($rq->senha),
            'api_token' => hash('sha256', $token)
        ]);

        $user->registrarLog('Cadastro no sistema');
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
            'senha' => 'required|string'
        ])->validate();

        if (Auth::attempt(['email' => $rq->email, 'password' => $rq->senha]))
        {
            $user = Auth::user();
            $token = Str::random(60);

            $user->fill([
                'api_token' => hash('sha256', $token)
            ])->save();
            $user->registrarLog('Token de acesso gerado');

            return response()->json([
                'token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function getDados(Request $rq)
    {
        $user = $rq->user();
        $user->foto = '/storage/' . $user->foto;
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function novaSenha(Request $rq)
    {
        Validator::make($rq->all(), [
            'senha_atual' => 'required|string',
            'senha_nova' => 'required|string|min:8'
        ])->validate();

        $user = $rq->user();
        if (Hash::check($rq->senha_atual, $user->password))
        {
            $user->fill(['password' => Hash::make($rq->senha_nova)])->save();
            $user->registrarLog('Senha alterada com sucesso');
            return response()->json([
                'message' => 'Senha alterada'
            ], 200);
        }

        $user->registrarLog('Tentativa falha de alteração de senha');
        return response()->json([
            'message' => 'Senha atual incorreta'
        ], 401);
    }

    public function atualizarFoto(Request $rq)
    {
        Validator::make($rq->all(), [
            'foto' => 'required|mimes:png,jpg,jpeg|max:2048'
        ])->validate();

        $user = $rq->user();
        if (!$user->fotoPadrao())
        {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $rq->file('foto')->store('fotos', 'public');
        $user->fill(['foto' => $path])->save();
        $user->registrarLog('Foto atualizada');

        return response()->json([
            'message' => 'Foto atualizada com sucesso',
            'url' => '/storage/' . $path
        ], 200);
    }

    public function deletarFoto(Request $rq)
    {
        $user = $rq->user();
        if (!$user->fotoPadrao())
        {
            Storage::disk('public')->delete($user->foto);
            $user->foto = $user->getFotoPadrao();
            $user->save();
            $user->registrarLog('Foto deletada');
        }

        return response()->json([
            'message' => 'Foto deletada com sucesso',
            'url' => '/storage/' . $user->foto,
        ], 200);
    }
}
