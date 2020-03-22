<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Org\Company::class, function (Faker $faker) {
    return [
        'company_name' => $faker->name,
        'company_access_type' => 'client',
        'company_contact' => $faker->uuid
    ];
});
