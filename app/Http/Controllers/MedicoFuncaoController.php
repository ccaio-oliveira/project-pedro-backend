<?php

namespace App\Http\Controllers;

use App\Models\MedicoFuncao;
use Illuminate\Http\Request;

class MedicoFuncaoController extends Controller
{
    protected $medico_funcao;

    public function __construct()
    {
        $this->medico_funcao = new MedicoFuncao();
    }

    public function getMedicoFuncao($id){
        $medico_funcao = $this->medico_funcao::all()->where('medico_id', '=', $id)->first();

        return $medico_funcao;
    }
}
