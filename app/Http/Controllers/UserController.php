<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLogin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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

    public function getUsersByType(Request $request){
        $tipo_usuario = $request->input('tipoUsuario');
        $perfil_usuario = $request->input('perfil_usuario');

        if($perfil_usuario == 1){
            $dados_usuarios = $this->user::all()->where('perfil_usuario', '=', $tipo_usuario);

            if($tipo_usuario == 2){
                foreach($dados_usuarios as $usuario){
                    $usuario->medico_crm = $this->medico_crm_controller->getMedicoCRM($usuario->id);
                }
            }

            // foreach($dados_usuarios as $usuario){
            //     $usuario->telefone = $this
            // }

            return response()->json($dados_usuarios);
        }

        if($perfil_usuario == 2){

            if($tipo_usuario == 2){
                $dados_usuarios = $this->user::all()->where('perfil_usuario', '=', $tipo_usuario);

                return response()->json($dados_usuarios);
            }
        }

        if($perfil_usuario == 3){
            $id_secretaria = DB::table('secretaria_medico')->where('medico_id', $tipo_usuario)->get();

            return response()->json($id_secretaria);
        }
    }
}
