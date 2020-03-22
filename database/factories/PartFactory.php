<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\Part::class, function (Faker $faker) {
    return [
        'box_id' => $faker->randomNumber(),
        'part_name' => 0,
        'part_description' => $faker->text,
        'project_id' => $faker->uuid,
        'created_by' => function() {
	        return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
        },
        'last_updated_by' => function() {
	        return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
        }
    ];
});
