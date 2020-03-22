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

$factory->define(NeubusSrm\Models\Org\Setting::class, function (Faker $faker) {
    return [
        'project_id' => function() {
            return factory(\NeubusSrm\Models\Org\Project::class)->create()->id;
        },
        'label' => $faker->name,
        'setting_key' => $faker->name,
        'value' => $faker->randomAscii
    ];
});
