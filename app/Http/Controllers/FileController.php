<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Relatorios;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    //
    protected $file;
    protected $relatorios;
    protected $usuarios;

    public function __construct()
    {
        $this->file = new File();
        $this->relatorios = new Relatorios();
        $this->usuarios = new UserController();
    }

    public function getFile($id){
        $file = $this->file::find($id);

        if(!$file){
            return response()->json([
                'message' => 'Arquivo não encontrado'
            ], 404);
        }

        return $file;
    }

    public function insertFileController($file){

        $fileName = $file->getClientOriginalName();
        $fileContent = file_get_contents($file->getRealPath());

        $newFile = $this->file::create([
            'nome' => $fileName,
            'arquivo' => $fileContent
        ]);

        return $newFile->id;
    }

    public function getFileDownload($id){
        $file = $this->file::find($id);

        if(!$file){
            return response()->json([
                'message' => 'Arquivo não encontrado'
            ], 404);
        }

        $response = new StreamedResponse(function() use ($file) {
            echo $file->arquivo;
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $file->nome . '"');

        return $response;
    }

    public function exportarRelatorios(Request $request){
        $grau = $request->input('prioridade');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        $status = $request->input('status');

        $relatorios = $this->relatorios::where('grau', $grau)
        ->whereBetween('data_criacao', [$dataInicio, $dataFim]);

        if($status != 0){
            $relatorios = $relatorios->where('status', $status);
        }

        $relatorios = $relatorios->get();

        $html = "<!DOCTYPE html>
        <html>
            <head>

            <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
            <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
            <link href=\"https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap\" rel=\"stylesheet\">

            <style>

                .capa {
                    font-family: \"Roboto\", sans-serif;

                    position: absolute;
                    left: 0;
                    right: 0;
                    top: 0;
                    bottom: 0;
                    margin: 0;
                    padding: 0;

                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;

                    text-align: center;
                }

                .titulo {
                    color: #164863;
                    font-size: 30px;
                    font-weight: 700;
                }

                .prioridade {
                    font-size: 20px;
                    font-weight: 700;
                    color: #164863;
                }

                .data {
                    font-size: 15px;
                    font-weight: 500;
                }

                .pagination {
                    position: absolute;
                    bottom: 0;
                    right: 0;
                    font-size: 10px;
                }

                .relatorios {
                    font-family: \"Roboto\", sans-serif;
                    margin: 0;
                    padding: 0;
                }

                .assunto {
                    font-size: 20px;
                    font-weight: 700;
                    color: #164863;

                    border-bottom: 1px solid #164863;
                }

                .assunto h2 {
                    margin: 0;
                    padding: 0;
                }

            </style>

            </head>

            <body>

            <div class=\"capa\">
                <h1 class=\"titulo\">Relatório de Achados Médicos</h1>
                <div class=\"prioridade\">Prioridade: ".($grau == 1 ? 'Prioritário' : ($grau == 2 ? 'Não Urgente' : 'Rotina'))."</div>
                <div class=\"data\">Data: " . date('d/m/Y') . "</div>
                <div class=\"pagination\">Página 1</div>
            </div>
            <pagebreak resetpagenum='1' />";

        foreach($relatorios as $relatorio){
            $aberto_por = $this->usuarios->getDadosUser($relatorio->aberto_por);
            $atrelado_a = $this->usuarios->getDadosUser($relatorio->atrelado_a);

            $html .= "<div class=\"relatorios\">
                            <div class=\"assunto\">
                                <h2>$relatorio->assunto</h2>
                            </div>

                            <div class=\"dados\">
                                <p><strong>Nome do Paciente:</strong> $relatorio->nome_paciente</p>
                                <p><strong>Data de Nascimento:</strong> " . date('d/m/Y', strtotime($relatorio->data_nascimento_paciente)) . "</p>
                                <p><strong>Grau de Urgência:</strong> ".($grau == 1 ? 'Prioritário' : ($grau == 2 ? 'Não Urgente' : 'Rotina'))."</p>
                                <p><strong>Aberto por:</strong> $aberto_por->nome_completo</p>
                                <p><strong>Atrelado a:</strong> $atrelado_a->nome_completo</p>
                                <p>
                                    <strong>Status:</strong>
                                    <span style=\"color: ".($relatorio->status == 1 ? "red" : "#289C00").";\">
                                        ".($relatorio->status == 1 ? 'Pendente' : 'Visualizado')."
                                    </span>
                                </p>
                            </div>
                        </div>
                        <pagebreak />";
        }

        $html .= "</body>
        </html>";

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P'
        ]);

        $mpdf->WriteHTML($html);

        $file = $mpdf->Output('relatorios.pdf', 'S');

        $response = new StreamedResponse(function() use ($file) {
            echo $file;
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="relatorios.pdf"');

        return $response;
    }
}
