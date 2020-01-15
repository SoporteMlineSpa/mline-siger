<?php

use Illuminate\Database\Seeder;

class EmpresasProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresas = \App\Empresa::all();
        $productos = \App\Producto::all();

        $productos->map(function ($producto) use ($empresas) {
            $empresas->map(function ($empresa) use ($producto) {
                $producto->empresas()->attach($empresa->id, ["precio" => 100]);
            });
        });
    }
}
