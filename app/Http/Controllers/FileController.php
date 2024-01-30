<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    //
    protected $file;

    public function __construct()
    {
        $this->file = new File();
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
}
