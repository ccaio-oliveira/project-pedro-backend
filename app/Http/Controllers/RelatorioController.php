<?php

namespace App\Http\Controllers;

use App\Models\Grau;
use App\Models\Relatorios;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    protected $relatorio;
    protected $grau;

    public function __construct()
    {
        $this->relatorio = new Relatorios();
        $this->grau = new Grau();
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
            $relatorio->data_criacao = date('d/m/Y', strtotime($relatorio->data_criacao));
        }

        return response()->json($relatorios);

    }
}
