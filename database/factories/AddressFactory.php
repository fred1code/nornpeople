<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'del' => $faker->state(),
        'col' => $faker->streetSuffix(),
        'numIn' => $faker->buildingNumber(),
        'numEx' => $faker->buildingNumber(),
        'street' => $faker->streetName(),
    ];
});


