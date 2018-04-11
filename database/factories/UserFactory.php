<?php

use Faker\Generator as Faker;
use App\User;
use App\Profile;
use App\Modelo;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(User::class, function (Faker $faker) {
    return [
        'nickname' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
        'register_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'last_connection' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'last_ip' => $faker->ipv4,
        'balance' => $faker->randomNumber($nbDigits = NULL, $strict = false),
    ];
});

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'lastname' => $faker->lastname,
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'), 
        'gender' => $faker->randomElement(['Mujer', 'Hombre', 'Transexual']),
        'country' => $faker->country,
        'state' => $faker->state,
        'city' => $faker->city,
        'phone' => $faker->e164PhoneNumber,
        'user_id' => $faker->unique()->numberBetween(1, App\User::count()),
    ];
});

$factory->define(Modelo::class, function (Faker $faker) {
    return [
        'age' => $faker->numberBetween($min = 18, $max = 70),
        'body_type' => $faker->randomElement(['Gordita', 'Flaquita', 'Atletica']),
        'weight' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 200), 
        'height' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 3), 
        'color_eye' => $faker->safeColorName,
        'about_me' => $faker->text($maxNbChars = 191),
        'about_show' => $faker->text($maxNbChars = 191),
        'user_id' => $faker->numberBetween(1, App\User::count()),
    ];
});
