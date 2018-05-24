<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'description' => $faker->sentence,
        'price' => $faker->numberBetween(1000, 50000),
        'category_id' => null,
    ];
});
