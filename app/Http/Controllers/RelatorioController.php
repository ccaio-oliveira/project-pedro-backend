<?php

namespace App\Http\Controllers;

use App\Models\Grau;
use App\Models\Relatorios;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    protected $relatorio;
    protected $grau;
    protected $usuario;
    protected $status_relatorio;

    public function __construct()
    {
        $this->relatorio = new Relatorios();
        $this->grau = new Grau();
        $this->usuario = new UserController();
        $this->status_relatorio = new StatusRelatorioController();
    }

    public function getRelatorios(Request $request){
        $grau =  $request->input('grau');
        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        $id_usuario = $request->input('id_usuario');

        $grau = $this->grau::all()->where('grau', '=', $grau)->first();

        $relatorios = $this->relatorio::where('aberto_por', $id_usuario)
        ->orWhere('atrelado_a', $id_usuario)
        ->where('grau', $grau->id);

        if($dataInicial != null && $dataFinal == null){
            $relatorios = $relatorios->where('data_criacao', '>=', $dataInicial);
        }

        if($dataFinal != null && $dataInicial == null){
            $relatorios = $relatorios->where('data_criacao', '<=', $dataFinal);
        }

        if($dataInicial != null && $dataFinal != null){
            $relatorios = $relatorios->whereBetween('data_criacao', [$dataInicial, $dataFinal]);
        }

        $relatorios = $relatorios->get();

        foreach($relatorios as $relatorio){
            $relatorio->data_criacao = date('H:i - d/m/Y', strtotime($relatorio->data_criacao));

            $relatorio->aberto_por = $this->usuario->getUsuariosPorId($relatorio->aberto_por);
            $relatorio->aberto_por = $relatorio->aberto_por->nome . ' ' . $relatorio->aberto_por->sobrenome;
            $relatorio->atrelado_a = $this->usuario->getUsuariosPorId($relatorio->atrelado_a);
            $relatorio->atrelado_a = $relatorio->atrelado_a->nome . ' ' . $relatorio->atrelado_a->sobrenome;

            $relatorio->status = $this->status_relatorio->getStatusRelatorio($relatorio->status)->nome;
        }

        return response()->json($relatorios);

    }

    public function getStatusRelatorio($id){
        $relatorio = $this->relatorio::all()->where('id', '=', $id)->first();

        return response()->json($relatorio);
    }
}
