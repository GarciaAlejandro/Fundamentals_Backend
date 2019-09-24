<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Product;
// Se denominan los parámetros para el Factory 
$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->Name,
        'price' => $faker->numberBetween($min = 10, $max = 200) ,

    ];
});
