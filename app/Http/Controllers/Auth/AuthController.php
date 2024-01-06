<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utils\Usuario\Login;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    protected $login;
    protected $model = Login::class;

    public function __construct()
    {
        $this->login = new Login();
    }

    protected function index(Request $request){
        return response()->json('ok');
    }

    protected function verificar(Request $request, string $hash = ''){
        try{
            $login = $this->login->getLoginByHash($hash);

            if(!$login->isEmpty()){
                $login = $login->first();

                $dados = unserialize($login->dados_login);

                return response()->json($dados);
            }
            session()->flush();
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch(\Exception $e){
            session()->flush();
            return response()->json([
                'error' => 'Unauthorized',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }
}
