<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    protected $secretaria;

    public function __construct()
    {
        $this->secretaria = new Secretaria();
    }

    public function getMedicoRelacionado($secretaria_id){
        $medico = $this->secretaria::all()->where('secretaria_id', '=', $secretaria_id);

        return $medico;
    }

    public function getSecretaria($id){
        $secretaria = $this->secretaria::all()->where('medico_id', '=', $id);

        return $secretaria;
    }
}
