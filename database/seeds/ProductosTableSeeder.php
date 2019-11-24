<?php

use App\Producto;
use Illuminate\Database\Seeder;

class ProductosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Producto::class, 30)->create();
  }
}
