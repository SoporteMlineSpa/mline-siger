<?php

use App\CompassRole;
use Illuminate\Database\Seeder;

class CompassRolesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = new CompassRole;
    $role->name = 'Compras';
    $role->save();

    $role->users()->save(factory(App\User::class)->make());

    $role = new CompassRole;
    $role->name = 'Despacho';
    $role->save();

    $role->users()->save(factory(App\User::class)->make());
  }
}
