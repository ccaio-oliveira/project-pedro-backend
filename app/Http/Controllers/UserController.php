<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserLogin;
use Exception;

class UserController extends Controller
{
    //
    public function login(Request $request){
        $usuario = $request->input('usuario_login');
        $senha = $request->input('senha_login');

        $senha = md5($senha);

        try {
            $dados_login = UserLogin::all()->where('usuario_login', '=', $usuario)->where('senha_login', '=', $senha);

            $dados_usuario = (array) json_decode(User::all()->where('id', '=', $dados_login[0]->usuario_id)[0]);

            foreach($dados_usuario as $key => $value){
                session()->put($key, $value);
            }

            $session = session()->all();

            echo '<pre>';
            print_r($session);

            // return $dados_usuario;
        } catch (Exception $e){

            throw new Exception($e);
        }

    }

    public function isLogged(){
        if(session()->all() !== ''){
            return 'authenticated';
        }
    }
}
