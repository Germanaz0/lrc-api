<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Service;
use Faker\Generator as Faker;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->realText(150),
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'country' => $faker->country,
        'state' => $faker->state,
        'zip_code' => $faker->postcode,
        'geolocation' => new Point($faker->latitude, $faker->longitude),
    ];
});
