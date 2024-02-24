<?php

namespace App\Http\Controllers;

use App\Mail\EmailForgotPassword;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    protected $user;
    protected $user_controller;
    protected $password_reset_controller;
    protected $user_login;

    public function __construct()
    {
        $this->user = new User();
        $this->user_controller = new UserController();
        $this->password_reset_controller = new PasswordResetController();
        $this->user_login = new UserLogin();
    }
    //
    public function login(Request $request){
        $usuario = $request->input('email');
        $senha = $request->input('senha_login');

        if(Auth::attempt(['email' => $usuario, 'password' => $senha], true)){
            $token = $request->user()->createToken('invoice')->plainTextToken;

            $idLogin = Auth::id();

            $dados_usuario = $this->user_controller->getDadosUser($usuario);

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

        $email = $request->input('email');

        $user = $this->user->where('email', $email)->first();

        if($user){
            $existingToken = $this->password_reset_controller->getTokenByEmail($email);

            if ($existingToken) {
                return response()->json([
                    "message" => "Email já enviado. Verifique sua caixa de entrada ou spam.",
                    "status" => 200
                ]);
            }

            $token = Str::random(60);

            $this->password_reset_controller->insertToken($email, $token);

            $mailer = new EmailForgotPassword($token);
            Mail::to($email)->send($mailer);

            return response()->json([
                "message" => "Email enviado com sucesso",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "message" => "Email não encontrado",
                "status" => 404
            ]);
        }
    }

    public function checkToken(Request $request){
        $token = $request->input('token');

        $token = $this->password_reset_controller->checkToken($token);

        if($token){
            return response()->json([
                "message" => "Token válido",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "message" => "Token inválido",
                "status" => 403,
                "token" => $token
            ]);
        }
    }

    public function resetPassword(Request $request){
        $token = $request->input('token');
        $password = $request->input('senha');

        $token = $this->password_reset_controller->checkToken($token);

        if($token){
            $user = $this->user_login->where('email', $token->email)->first();
            $user->password = Hash::make($password);
            $user->save();

            $this->password_reset_controller->deleteToken($token->email);

            return response()->json([
                "message" => "Senha alterada com sucesso",
                "status" => 200
            ]);
        } else {
            return response()->json([
                "message" => "Token inválido",
                "status" => 403
            ]);
        }
    }
}
