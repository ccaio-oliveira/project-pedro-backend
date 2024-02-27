<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Relatorios;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

        $relatorios = $this->relatorios::where('grau', $grau);

        if($dataInicio != null && $dataFim != null){
            $relatorios->whereBetween('created_at', [$dataInicio, $dataFim]);
        }else if($dataInicio != null){
            $relatorios->where('created_at', '>=', $dataInicio);
        }else if($dataFim != null){
            $relatorios->where('created_at', '<=', $dataFim);
        }

        if($status != 0){
            $relatorios = $relatorios->where('status', $status);
        }

        $relatorios = $relatorios->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Assunto');
        $sheet->setCellValue('C1', 'Nome do Paciente');
        $sheet->setCellValue('D1', 'Data de Nascimento');
        $sheet->setCellValue('E1', 'Grau de Urgência');
        $sheet->setCellValue('F1', 'Aberto por');
        $sheet->setCellValue('G1', 'Enviado para');
        $sheet->setCellValue('H1', 'Status');

        $row = 2;

        foreach($relatorios as $relatorio){
            $atrelado_a = $this->usuarios->getDadosUser($relatorio->atrelado_a);
            $aberto_por = $this->usuarios->getDadosUser($relatorio->aberto_por);

            $sheet->setCellValue('A' . $row, $relatorio->id);
            $sheet->setCellValue('B' . $row, $relatorio->assunto);
            $sheet->setCellValue('C' . $row, $relatorio->nome_paciente);
            $sheet->setCellValue('D' . $row, date('d/m/Y', strtotime($relatorio->data_nascimento_paciente)));
            $sheet->setCellValue('E' . $row, $grau == 1 ? 'Prioritário' : ($grau == 2 ? 'Não Urgente' : 'Rotina'));
            $sheet->setCellValue('F' . $row, $aberto_por->nome_completo);
            $sheet->setCellValue('G' . $row, $atrelado_a->nome_completo);
            $sheet->setCellValue('H' . $row, $relatorio->status == 1 ? 'Pendente' : 'Visualizado');

            $row++;
        }

        // Gera o arquivo Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'relatorios_' . date('d-m-Y') . '.xlsx';

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');

        return $response;
    }
}
