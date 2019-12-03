<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
  return [
    'sku' => $faker->randomNumber(6),
    'detalle' => $faker->company(),
    'stock' => $faker->numberBetween(0, 100),
    'precio' => ($faker->numberBetween(1000000, 5000000))
  ];
});
