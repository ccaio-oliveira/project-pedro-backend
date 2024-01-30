<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    //
    protected $file;

    public function __construct()
    {
        $this->file = new File();
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
}
