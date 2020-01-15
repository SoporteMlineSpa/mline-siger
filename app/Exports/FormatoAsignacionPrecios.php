<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoAsignacionPrecios implements FromCollection
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $header = collect(['SKU', 'Detalle', 'Precio Costo', 'Porcentaje Ganancia', 'Neto', 'Precio Venta']);
        $body = collect([]);

        $productos = \App\Producto::all();

        $productos->map(function ($producto, $index) use ($body) {
            $rowCell = $index + 2;
            $row = collect([$producto->sku, $producto->detalle, $producto->costo, "", "=C$rowCell * (C$rowCell * (D$rowCell / 100))", "=E$rowCell * 1.19"]);
            $body->push($row);
        });

        return collect([
            $header, $body
        ]);
    }
}
