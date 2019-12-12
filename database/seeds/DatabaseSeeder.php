<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(CompassRolesTableSeeder::class);
    $this->call(ClientesSeeder::class);
    $this->call(UsersTableSeeder::class);
    $this->call(ProductosTableSeeder::class);
    $this->call(EmpresaProductoTableSeeder::class);
  }
}
