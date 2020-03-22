<?php

use Faker\Generator as Faker;

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

$factory->define(NeubusSrm\Models\Auth\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => uniqid(md5(time()), true).$faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'company_id' => function() {
            return factory(\NeubusSrm\Models\Org\Company::class)->create()->id;
        },
	    'verification_token_id' => function() {
		    return factory(\NeubusSrm\Models\Auth\VerificationToken::class)->create()->id;
	    },
        'role' => \NeubusSrm\Models\Auth\User::ROLE_CLIENT,
	    'has_mfa' => false
    ];
});
