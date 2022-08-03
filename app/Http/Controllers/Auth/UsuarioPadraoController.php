<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuarioPadraoController extends Controller
{
    public function get_usuario()
    {
        $_COOKIE['token_jwt'];
        $response = Http::withToken($_COOKIE['token_jwt'], 'Bearer')->get('http://localhost:8000/api/auth/usuario/venda-externa');
        $response = $response->object();
        $usuario = User::find($response->user->id);
        // if (!$usuario) {
        //     $usuario = User::create([
        //         'id' => $response->user->id,
        //         'loja' =>  $response->user->loja_id,
        //         'name' => $response->user->name,
        //         'email' => $response->user->email,
        //         'password' => $response->p,
        //     ]);
        // }
        // else {
        //     # code...
        // }

        return response()->json($usuario);
    }
}
