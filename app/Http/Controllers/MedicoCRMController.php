<?php

namespace App\Http\Controllers;

use App\Models\MedicoCRM;
use Illuminate\Http\Request;

class MedicoCRMController extends Controller
{
    //
    protected $medico_crm;

    public function __construct()
    {
        $this->medico_crm = new MedicoCRM();
    }

    public function getMedicoCRM($id){
        $medico_crm = $this->medico_crm::all()->where('usuario_id', '=', $id)->first();
        return $medico_crm;
    }

    public function createDoctorCRM($user_id, $crm){
        $this->medico_crm->usuario_id = $user_id;
        $this->medico_crm->crm = $crm;
        $this->medico_crm->save();
    }
}
