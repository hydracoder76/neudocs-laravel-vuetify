<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Org\Request::class, function (Faker $faker) {
    return [
        'company_id' => $faker->uuid,
        'created_by' => $faker->uuid,
        'updated_by' => $faker->uuid,
        'is_fulfilled' => $faker->boolean,
        'is_in_process' => $faker->boolean,
        'fulfilled_by' => $faker->uuid,
        'fulfilled_on' => \Carbon\Carbon::now(),
        'request_name' => $faker->name,
        'external_comment' => $faker->text
    ];
});
