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


    public function createFotoPerfil($user_id, $file){
        $this->foto_perfil->usuario_id = $user_id;
        $this->foto_perfil->foto = $file;

        $image = $this->foto_perfil->save();

        return $this->foto_perfil;
    }
}
