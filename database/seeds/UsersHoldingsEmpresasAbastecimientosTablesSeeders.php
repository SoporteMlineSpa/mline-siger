<?php

use Illuminate\Database\Seeder;

class UsersHoldingsEmpresasAbastecimientosTablesSeeders extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    factory(App\Holding::class, 3)->create()->each(function($holding) {
      $holding->users()->save(factory(App\User::class)->make());
      $holding->empresas()
              ->saveMany( factory(App\Empresa::class, 3)->make() )
              ->each(function($empresa){
                $empresa->users()->save(factory(App\User::class)->make());
                $empresa->abastecimientos()
                        ->saveMany( factory(App\Abastecimiento::class, 3)->make() )
                        ->each(function($abastecimiento){
                          $abastecimiento->users()->save(factory(App\User::class)->make());
                        });
              });
    });

  }
}

