<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        //Validar los datos de entrada
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        //Autenticar al usuario
        if (auth()->attempt($credenciales)) {
            $user = auth()->user(); //Obtener el usuario autenticado
            $token = $user->createToken($request->email)->plainTextToken; //Crear el token (el nombre del token es el email)
            return response()->json(['success' => true, 'token' => $token], 200); //Retornar el token
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401); //401: Unauthorized
        }
    }
}