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

    public function createSecretary($user_id, $doctor_id){
        $this->secretaria->secretaria_id = $user_id;
        $this->secretaria->medico_id = $doctor_id;
        $this->secretaria->save();
    }

    public function getMedicoRelacionado($secretaria_id){
        $medico = $this->secretaria::all()->where('secretaria_id', '=', $secretaria_id)->first();

        return $medico;
    }

    public function getSecretaria($id){
        $secretaria = $this->secretaria::all()->where('medico_id', '=', $id);

        return $secretaria;
    }
}
