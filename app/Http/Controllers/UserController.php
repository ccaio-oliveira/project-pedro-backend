<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLogin;

class UserController extends Controller
{
    //
    public function login(Request $request){
        $usuario = $request->input('usuario_login');
        $senha = $request->input('senha_login');

        $senha = md5($senha);

        return UserLogin::all()->where('usuario_login', '=', $usuario)->where('senha_login', '=', $senha);
    }
}
