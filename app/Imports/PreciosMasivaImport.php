<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class PreciosMasivaImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    private $empresa;

    /**
     * @param $empresa
     */
    public function __construct($empresa)
    {
        $this->empresa = \App\Empresa::findOrFail($empresa);
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $this->empresa->productos()->detach();
        $collection->map(function($row) {
            if (!is_null($row['precio_venta']) && ($row['precio_venta'] > 0)) {
                $producto = \App\Producto::where('sku', $row['sku'])->first();
                $this->empresa->productos()->attach($producto->id, ["precio" => $row['neto']]);
            }
        });
        $this->empresa->saveOrFail();
    }
}
