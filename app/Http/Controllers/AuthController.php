<?php

namespace App\Http\Controllers;

use App\Mail\EmailForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        $usuario = $request->input('email');
        $senha = $request->input('senha_login');

        if(Auth::attempt(['email' => $usuario, 'password' => $senha])){
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
        Auth::logout();
        Cookie::forget('laravel_session');
        Cookie::forget('XSRF-TOKEN');
    }

    public function forgotPassword(Request $request){
        $mailer = new EmailForgotPassword(Hash::make($request->input('email')));

        $email = $request->input('email');

        $user = $this->user->where('email', $email)->first();

        if($user){

            Mail::to($email)->send($mailer);

            return response()->json([
                "message" => "Email enviado com sucesso",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "message" => "Email não encontrado",
                "status" => 404
            ]);}
    }
}
