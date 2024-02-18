<?php

namespace App\Http\Controllers;

use App\Models\FotoPerfil;
use Illuminate\Http\Request;

class FotoPerfilController extends Controller
{
    protected $foto_perfil;

    public function __construct()
    {
        $this->foto_perfil = new FotoPerfil();
    }

    public function getFotoPerfil($id){
        $foto = $this->foto_perfil::all()->where('id', $id)->first();

        return $foto;
    }
}
