<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Grau;
use App\Models\Relatorios;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    protected $relatorio;
    protected $grau;
    protected $usuario;
    protected $status_relatorio_controller;
    protected $file_controller;

    public function __construct()
    {
        $this->relatorio = new Relatorios();
        $this->grau = new Grau();
        $this->usuario = new UserController();
        $this->status_relatorio_controller = new StatusRelatorioController();
        $this->file_controller = new FileController();
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

            $relatorio->aberto_por = $this->usuario->getDadosUser($relatorio->aberto_por);
            $relatorio->aberto_por = $relatorio->aberto_por->nome . ' ' . $relatorio->aberto_por->sobrenome;
            $relatorio->atrelado_a = $this->usuario->getDadosUser($relatorio->atrelado_a);
            $relatorio->atrelado_a = $relatorio->atrelado_a->nome . ' ' . $relatorio->atrelado_a->sobrenome;

            $relatorio->status = $this->status_relatorio_controller->getStatusRelatorio($relatorio->status)->nome;

            if($relatorio->arquivo != null){
                $file = $this->file_controller->getFile($relatorio->arquivo);

                $relatorio->arquivo = $file->nome;
                $relatorio->arquivo_id = $file->id;
            }
        }

        return response()->json($relatorios);

    }

    public function getStatusRelatorio($id){
        $relatorio = $this->relatorio::all()->where('id', '=', $id)->first();

        return response()->json($relatorio);
    }

    public function createRelatorio(Request $request){

        $file = null;

        if($request->hasFile('arquivo')){
            $file = $request->file('arquivo');
            $file = $this->file_controller->insertFileController($file);

        }

        $this->relatorio->aberto_por = $request->input('aberto_por');
        $this->relatorio->atrelado_a = $request->input('atrelado_a');
        $this->relatorio->nome_paciente = $request->input('nome_paciente');
        $this->relatorio->grau = $request->input('grau');
        $this->relatorio->assunto = $request->input('assunto');
        $this->relatorio->status = 1;
        $this->relatorio->data_criacao = date('Y-m-d H:i:s');
        $this->relatorio->arquivo = $file;

        $createRelatorio = $this->relatorio->save();

        return response()->json($createRelatorio);
    }
}
