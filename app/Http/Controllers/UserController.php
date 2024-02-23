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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\VarDumper\VarDumper;

class UserController extends Controller
{
    //
    protected $user_login;
    protected $user;
    protected $session_controller;
    protected $medico_crm_controller;
    protected $secretaria_controller;
    protected $telefone_controller;
    protected $medico_funcao_controller;
    protected $foto_perfil_controller;

    public function __construct()
    {
        $this->user_login = new UserLogin();
        $this->user = new User();
        $this->session_controller = new SessionController();
        $this->medico_crm_controller = new MedicoCRMController();
        $this->secretaria_controller = new SecretariaController();
        $this->telefone_controller = new TelefoneController();
        $this->medico_funcao_controller = new MedicoFuncaoController();
        $this->foto_perfil_controller = new FotoPerfilController();
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

        $dados_usuarios = $this->user->where('perfil_usuario', '=', $tipo_usuario)->get();

        // se o usuário for médico, buscar o CRM
        if($tipo_usuario == 2){
            foreach($dados_usuarios as $usuario){
                $usuario->medico_crm = $this->medico_crm_controller->getMedicoCRM($usuario->id)->crm;
                $usuario->funcao = $this->medico_funcao_controller->getMedicoFuncao($usuario->id)->funcao;
            }
        }

        // buscar os telefones dos usuários
        foreach($dados_usuarios as $usuario){
            $usuario->telefone_whats = $this->getUserTelefone($usuario->id, 'whatsapp')->telefone;
            $usuario->telefone_cel = $this->getUserTelefone($usuario->id, 'celular')->telefone;
        }

        return response()->json($dados_usuarios);
    }

    public function getUserTelefone($id, $tipo){
        $telefone = $this->telefone_controller->getTelefone($id, $tipo);

        return $telefone;
    }

    public function getUsuarioPerfil(Request $request){
        $id = $request->input('usuario_id');

        $dados_usuario = $this->user::all()->where('id', '=', $id)->first();

        if($dados_usuario->perfil_usuario == 2){
            $dados_usuario->medico_crm = $this->medico_crm_controller->getMedicoCRM($id)->crm;
            $dados_usuario->especialidade = $this->medico_funcao_controller->getMedicoFuncao($dados_usuario->especialidade)->funcao;
        }

        if($dados_usuario->foto_id != 0){
            $dados_usuario->foto_id = $this->foto_perfil_controller->getFotoPerfil($dados_usuario->foto_id);
            $dados_usuario->foto_id->foto = asset('storage/' . $dados_usuario->foto_id->foto);
        }

        $dados_usuario->telefone_whats = $this->getUserTelefone($id, 'whatsapp')->telefone;
        $dados_usuario->telefone_cel = $this->getUserTelefone($id, 'celular')->telefone;

        return response()->json($dados_usuario);
    }

    public function changeEmail(Request $request){
        $email_antigo = $request->input('email');
        $novo_email = $request->input('newEmail');

        $user = $this->user->where('email', '=', $email_antigo)->first();
        $user_login = $this->user_login->where('email', '=', $email_antigo)->first();

        $check_email = $this->user->where('email', '=', $novo_email)->first();

        if($check_email){
            return response()->json(['message' => 'Email já cadastrado', 'status' => 400]);
        }

        $user->email = $novo_email;
        $user_login->email = $novo_email;

        $user->save();
        $user_login->save();

        return response()->json(['message' => 'Email alterado com sucesso!', 'status' => 200]);
    }

    public function changeNumero(Request $request){
        $id = $request->input('usuario_id');
        $novo_numero = $request->input('telefone');
        $tipo = $request->input('tipo');

        $telefone = $this->telefone_controller->getTelefone($id, $tipo);

        $telefone->telefone = $novo_numero;
        $telefone->save();

        return response()->json(['message' => 'Número alterado com sucesso!', 'status' => 200]);
    }

    public function changeUsername(Request $request){
        $id = $request->input('usuario_id');
        $novo_nome = $request->input('nome_completo');

        $user = $this->user->where('id', '=', $id)->first();

        $user->nome_completo = $novo_nome;

        $user->save();

        return response()->json(['message' => 'Nome alterado com sucesso!', 'status' => 200]);
    }

    public function validateCurrentPassword(Request $request){
        $id = $request->input('user_id');
        $password = $request->input('password');

        $user_login = $this->user_login->where('usuario_id', '=', $id)->first();

        if(Hash::check($password, $user_login->password)){
            return response()->json(['message' => 'Senha válida', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Senha inválida', 'status' => 400]);
        }
    }

    public function changePassword(Request $request){
        $id = $request->input('user_id');
        $new_password = $request->input('new_password');

        $user_login = $this->user_login->where('usuario_id', '=', $id)->first();

        $user_login->password = Hash::make($new_password);

        $user_login->save();

        return response()->json(['message' => 'Senha alterada com sucesso!', 'status' => 200]);
    }


    public function uploadProfile(Request $request){
        $id = $request->input('user_id');
        $file = $request->file('file');

        $user = $this->user->where('id', '=', $id)->first();

        $path = $file->store('images', 'public');

        if($user->foto_id != 0){
            $profile_pic = $this->foto_perfil_controller->getFotoPerfil($user->foto_id);

            Storage::delete('public/' . $profile_pic->foto);

            $profile_pic->foto = $path;
            $profile_pic->save();
        } else {
            $profile_pic = $this->foto_perfil_controller->createFotoPerfil($user->id, $path);
        }

        $user->foto_id = $profile_pic->id;

        $user->save();

        return response()->json(['message' => 'Foto de perfil alterada com sucesso!', 'status' => 200]);
    }
}
