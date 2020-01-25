<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoPack implements FromCollection
{
    private $empresa;
    private $totalGasto;
    private $nroRequerimientos;
    private $nroProductos;
    private $header;

    /**
     * @param \App\Producto $producto
     * @param String $inicio
     * @param String $fin
     */
    public function __construct(\App\Empresa $empresa, String $inicio, String $fin)
    {
        $this->empresa = $empresa;
    }
        
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            $this->header,
            $body
        ]);
    }
}
