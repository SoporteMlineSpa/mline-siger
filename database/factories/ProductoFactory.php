<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
  return [
    'familia' => $faker->colorName,
    'detalle' => $faker->city,
    'marca' => $faker->country,
    'formato' => $faker->word,
    'precio' => ($faker->numberBetween(1000000, 5000000))/100
  ];
});
