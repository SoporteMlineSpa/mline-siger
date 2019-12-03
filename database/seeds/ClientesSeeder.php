<?php

use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    $abastecimiento = new App\Abastecimiento;
    $abastecimiento->nombre = 'Eurest CL Puerto Montt';
    $abastecimiento->save();

    $australis = new App\Empresa;
    $australis->razon_social = 'Australis Mar S.A';
    $australis->rut = '76.003.885-7';
    $australis->direccion = 'Santa Rosa #560, Oficina 15 A, Puerto Varas';

    $abastecimiento->empresas()->save($australis);

    $australis->centros()->saveMany([
      new App\Centro(['nombre' => 'Costa']),
      new App\Centro(['nombre' => 'Humos 2']),
      new App\Centro(['nombre' => 'Traiguén']),
      new App\Centro(['nombre' => 'Humos 3']),
      new App\Centro(['nombre' => 'Humos 5']),
      new App\Centro(['nombre' => 'Humos 6']),
      new App\Centro(['nombre' => 'Melchor 4']),
      new App\Centro(['nombre' => 'Moraleda']),
    ]);
    $australis->users()->save(
      factory(App\User::class)->make()
    );

    $blumar = new App\Empresa;
    $blumar->razon_social = 'Salmones Blumar S.A';
    $blumar->rut = '76653690-5';
    $blumar->direccion = 'Avenida Juan Soler Manfredini 11 Of 1202, Puerto Montt';

    $abastecimiento->empresas()->save($blumar);

    $blumar->centros()->saveMany([
      new App\Centro(['nombre' => 'Caicura']),
      new App\Centro(['nombre' => 'Chivato 1']),
      new App\Centro(['nombre' => 'Chivato 2']),
      new App\Centro(['nombre' => 'Concheo 2']),
      new App\Centro(['nombre' => 'Dring 3']),
      new App\Centro(['nombre' => 'Dring 1']),
      new App\Centro(['nombre' => 'Elena NW']),
      new App\Centro(['nombre' => 'Ester']),
      new App\Centro(['nombre' => 'Forsyth']),
      new App\Centro(['nombre' => 'Level']),
      new App\Centro(['nombre' => 'Midhurst']),
      new App\Centro(['nombre' => 'Ninualac 1']),
      new App\Centro(['nombre' => 'Ninualac 2']),
      new App\Centro(['nombre' => 'Orestes']),
      new App\Centro(['nombre' => 'Punta Rouse']),
      new App\Centro(['nombre' => 'Se Forsyth']),
      new App\Centro(['nombre' => 'Tangbac']),
      new App\Centro(['nombre' => 'Team Baños 1']),
      new App\Centro(['nombre' => 'Team Depto Baños 2']),
      new App\Centro(['nombre' => 'Victoria'])
    ]);
    $blumar->users()->save(factory(App\User::class)->make());

    $fiordos = new App\Empresa;
    $fiordos->razon_social = 'Exportadora Los Fiordos Limitada';
    $fiordos->rut = '79872420-7';
    $fiordos->direccion = 'Avenida Diego Portales n° 2000, Puerto Montt';

    $abastecimiento->empresas()->save($fiordos);

    $fiordos->centros()->saveMany([
      new App\Centro(['nombre' => 'Acuario']),
      new App\Centro(['nombre' => 'Angostura']),
      new App\Centro(['nombre' => 'Bahia Anita']),
      new App\Centro(['nombre' => 'Catalina']),
      new App\Centro(['nombre' => 'Esperanza']),
      new App\Centro(['nombre' => 'Estero Nieto']),
      new App\Centro(['nombre' => 'Estero Soto']),
      new App\Centro(['nombre' => 'Gala 1']),
      new App\Centro(['nombre' => 'Graffer']),
      new App\Centro(['nombre' => 'Isla Suarez']),
      new App\Centro(['nombre' => 'Marta']),
      new App\Centro(['nombre' => 'Martina']),
      new App\Centro(['nombre' => 'Melimoyu']),
      new App\Centro(['nombre' => 'Piscicultura Magdalena']),
      new App\Centro(['nombre' => 'Punta Ganso']),
      new App\Centro(['nombre' => 'San Andres']),
      new App\Centro(['nombre' => 'Villegas']),
      new App\Centro(['nombre' => 'Bodega Cisnes']),
      new App\Centro(['nombre' => 'Base Melinka']),
      new App\Centro(['nombre' => 'Betecoi']),
      new App\Centro(['nombre' => 'Canal Perez Norte']),
      new App\Centro(['nombre' => 'Carabelas']),
      new App\Centro(['nombre' => 'Chaffer']),
      new App\Centro(['nombre' => 'Concoto']),
      new App\Centro(['nombre' => 'Cuptana 9']),
      new App\Centro(['nombre' => 'Elena']),
      new App\Centro(['nombre' => 'Garrao 2']),
      new App\Centro(['nombre' => 'Isla May']),
      new App\Centro(['nombre' => 'Lagreze']),
      new App\Centro(['nombre' => 'Luna 2']),
      new App\Centro(['nombre' => 'Navarro']),
      new App\Centro(['nombre' => 'Serrano']),
      new App\Centro(['nombre' => 'Sierra']),
      new App\Centro(['nombre' => 'Valverde 4']),
      new App\Centro(['nombre' => 'Valverde 5']),
      new App\Centro(['nombre' => 'Valverde 6']),
      new App\Centro(['nombre' => 'Verdugo 1']),
      new App\Centro(['nombre' => 'Verdugo 2']),
      new App\Centro(['nombre' => 'Tecnico Cabañas Piscicultura Hornopiren']),
      new App\Centro(['nombre' => 'Tecnico Administración Piscicultura Hornopiren'])
    ]);
    $fiordos->users()->save(factory(App\User::class)->make());

    $aqua = new App\Empresa;
    $aqua->razon_social = 'Empresas Aquachile S.A';
    $aqua->rut = '86247400-7';
    $aqua->direccion = 'Cardonal s/n Lote B, Puerto Montt';

    $abastecimiento->empresas()->save($aqua);

    $aqua->centros()->saveMany([
      new App\Centro(['nombre' => 'Avellano']),
      new App\Centro(['nombre' => 'Cascada']),
      new App\Centro(['nombre' => 'Pangal 1']),
      new App\Centro(['nombre' => 'Pangal 2']),
      new App\Centro(['nombre' => 'Pangal 3']),
      new App\Centro(['nombre' => 'Punta Gonzalez']),
    ]);
    $aqua->users()->save(factory(App\User::class)->make());

    $centros = App\Centro::all();
    foreach ($centros as $centro) {
      $centro->users()->save(factory(App\User::class)->make());
    }
  }
}
