<?php

use Faker\Generator as Faker;

$factory->define(\NeubusSrm\Models\Auth\VerificationToken::class, function (Faker $faker) {
    return [
        'token' => $faker->text
    ];
});
