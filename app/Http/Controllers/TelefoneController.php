<?php

namespace App\Http\Controllers;

use App\Models\Telefone;
use Illuminate\Http\Request;

class TelefoneController extends Controller
{
    //
    protected $telefone;

    public function __construct()
    {
        $this->telefone = new Telefone();
    }

    public function getTelefone($id, $tipo){
        $telefone = $this->telefone::all()->where('usuario_id', '=', $id)->where('tipo_telefone', '=', $tipo)->first();

        return $telefone;
    }

    public function createPhone($user_id, $telefone, $tipo){
        $phone = "55" . preg_replace("/[^0-9]/", '', $telefone);
        $telefone = new Telefone();
        $telefone->usuario_id = $user_id;
        $telefone->telefone = $phone;
        $telefone->tipo_telefone = $tipo;
        $telefone->save();
    }
}
