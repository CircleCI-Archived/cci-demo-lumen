<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

/**
 * These won't be real Pokémon but I'm lazy.
 */
$factory->define(App\Pokemon::class, function ($faker) {
    return [
        'name' => $faker->word,
        'number' => $faker->numberBetween(1, 998),
        'description' => $faker->text,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
