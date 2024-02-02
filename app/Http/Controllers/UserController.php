<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLogin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    protected $user_login;
    protected $user;
    protected $session_controller;
    protected $medico_crm_controller;

    public function __construct()
    {
        $this->user_login = new UserLogin();
        $this->user = new User();
        $this->session_controller = new SessionController();
        $this->medico_crm_controller = new MedicoCRMController();
    }

    public function getDadosUser($id){
        $dados_usuario = $this->user::all()->where('id', '=', $id)->first();

        if($dados_usuario->perfil_usuario == 2){
            $dados_usuario->medico_crm = $this->medico_crm_controller->getMedicoCRM($id);
        }

        return $dados_usuario;
    }

    public function getUsuarios(){
        $dados_usuarios = $this->user::all();

        return response()->json($dados_usuarios);
    }

    public function getUsuariosPorId($id){
        $dados_usuarios = $this->user::all()->where('id', '=', $id)->first();

        return $dados_usuarios;
    }

    public function isLogged(Request $request){
        $token = Auth::user();
        echo $token;
        // return response()->json($request->user());

        // if(count($sessao) > 0){
        //     return $sessao;
        // } else {
        //     return 'not authenticated';
        // }
    }
}
