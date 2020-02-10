<?php

namespace App\Http\Controllers;

use App\Transporte;
use Illuminate\Http\Request;

class TransporteController extends Controller
{
    /**
     * Genera los PDFs de guias para despachar
     *
     * @return \Illuminate\Http\Response
     */
    public function generarFormatosDespacho(\App\Transporte $transporte)
    {
        $requerimientos = $transporte->requerimientos()->get();
        
        $pdfs = $requerimientos->map(function($requerimiento) {
            return $requerimiento->generarPdf();
        });

        $file = public_path("/storage/OUTZIP/$transporte->id.zip");
        $zip = new \ZipArchive();
        if ($zip->open($file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach($pdfs as $requerimiento){
                foreach ($requerimiento as $guia) {
                    $relativeName = basename($guia);
                    $zip->addFile($guia, $relativeName);
                }
            };

            $zip->close();
        }

        return response()->download($file);
    }
    
}
