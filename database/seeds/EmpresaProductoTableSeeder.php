<?php

use Illuminate\Database\Seeder;

class EmpresaProductoTableSeeder extends Seeder
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
        foreach ($empresas as $empresa) {
            $empresa->productos()->attach($productos);
        }
    }
}
