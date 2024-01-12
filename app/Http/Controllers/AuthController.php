<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $user;
    protected $user_controller;

    public function __construct()
    {
        $this->user = new User();
        $this->user_controller = new UserController();
    }
    //
    public function login(Request $request){
        $usuario = $request->input('usuario_login');
        $senha = $request->input('senha_login');

        if(Auth::attempt(['usuario_login' => $usuario, 'password' => $senha])){
            $token = $request->user()->createToken('invoice')->plainTextToken;

            $idLogin = Auth::id();

            $dados_usuario = $this->user_controller->getDadosUser($idLogin);

            $data = [
                'token' => $token,
                'data' => $dados_usuario
            ];

            return $data;
        }

        return response()->json([
            "message" => "Not Authorized",
            "status" => 403
        ]);

    }

    public function isLogged(){
        $data = [
            "message" => 'Authorized',
            "status" => 200
        ];

        return $data;
    }

    public function logout(){

    }
}
