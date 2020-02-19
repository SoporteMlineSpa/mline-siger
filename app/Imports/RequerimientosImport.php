<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RequerimientosImport implements ToCollection, WithHeadingRow
{

    public function __construct(\App\Centro $centro)
    {
        $this->centro = $centro;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $centro = $this->centro;
        $requerimiento = new \App\Requerimiento;
        $requerimiento->nombre = date("Y-m-d") . ' '
            .$centro->empresa->razon_social . ': '
            .$centro->nombre . ': '
            .(is_null(\App\Requerimiento::latest()->first()) ? 0 : \App\Requerimiento::latest()->first()->id);
        $requerimiento->estado = "EN PROCESAMIENTO";
        $centro->requerimientos()->save($requerimiento);

        foreach ($collection as $row)
        {
            $producto = $centro->empresa->productos->where('sku', $row['sku'])->first();
            if (!is_null($producto)) {
                $requerimiento->productos()->attach($producto, ["cantidad" => $row['cantidad'], "precio" => $producto->pivot->precio]);
            }
        }
    }
}
