<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthUsuarios extends Controller
{
    public function store_user(Request $request)
    {
        $usuario = User::create($request->all());

        if ($usuario) {
            return response()->json(['success' => true], 200);
        }else {
            return response()->json(['success' => false], 200);
        }
    }

    public function update_user(Request $request, $id)
    {
        $usuario = User::where('user_alltech', $id)->first();
        if ($usuario) {
             $usuario->update($request->all());
            return response()->json(['success' => $request->name], 200);
        }else {
            return response()->json(['success' => false], 200);
        }
    }
}
