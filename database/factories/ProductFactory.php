<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Product;
// Se denominan los parámetros para el Factory 
$factory->define(Product::class, function (Faker $faker) {
    return [
        'id' =>  $faker->unique()->randomDigit,
        'created_at' => now(),
        'updated_at' => now(),
        'name' => $faker->Name,
        'price' => $faker->numberBetween($min = 10, $max = 200) ,

    ];
});
