<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoPack implements FromCollection
{
    private $empresa;
    private $requerimientos;
    private $totalGasto;
    private $nroRequerimientos;
    private $header;

    /**
     * @param \App\Producto $producto
     * @param $inicio
     * @param $fin
     */
    public function __construct(\App\Empresa $empresa, $inicio, $fin)
    {
        $this->empresa = $empresa;
        $this->requerimientos = $empresa->centros->map(function($centro) use ($inicio, $fin) {
            return $centro->requerimientos()
                          ->whereDate('created_at', '>=', $inicio)
                          ->whereDate('created_at', '<=', $fin)
                          ->get();
        })->flatten();
        $this->nroRequerimientos = $this->requerimientos->count();
        $this->totalGasto = $this->requerimientos->reduce(function($carry, $requerimiento) {
            return $carry + $requerimiento->getTotal();
        });
        $this->header = collect([]);
        $this->header->push(['Razon Social', 'RUT', 'Periodo Inicio', 'Periodo Fin', 'Total Ventas ($)', 'Numero de Pedidos']);
        $this->header->push([$this->empresa->razon_social, $this->empresa->rut, $inicio, $fin, number_format(round($this->totalGasto), 0), $this->nroRequerimientos]);
        $this->header->push([]);
        $this->header->push(['Fecha', 'Bodega Origen', 'Tratamiento', 'Destino', 'N° Guia', 'Cantidad', 'Producto', 'P. Unitario', 'Total', 'Observaciones']);
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $body = collect([]);
        $this->requerimientos->map(function($requerimiento) use($body) {
            $requerimiento->productos->map(function($producto) use ($requerimiento, $body) {
                $body->push([
                    $requerimiento->created_at,
                    'PTO MONTT',
                    'VTA',
                    $requerimiento->centro->nombre,
                    $requerimiento->folio,
                    $producto->pivot->real,
                    $producto->detalle,
                    number_format($producto->pivot->precio),
                    number_format(round($producto->pivot->precio * $producto->pivot->real), 0),
                    $requerimiento->observaciones
                ]);
            });
        });

        return collect([
            $this->header,
            $body
        ]);
    }
}
