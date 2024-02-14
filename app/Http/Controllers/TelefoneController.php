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
}
