<?php

namespace App\Http\Controllers;

use App\Models\StatusRelatorio;
use Illuminate\Http\Request;

class StatusRelatorioController extends Controller
{
    private $status_relatorio;

    public function __construct()
    {
        $this->status_relatorio = new StatusRelatorio();
    }

    public function getStatusRelatorio($id){
        $status_relatorio = $this->status_relatorio::all()->where('id', '=', $id)->first();

        return $status_relatorio;
    }
}
