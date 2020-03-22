<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\FileUpload::class, function (Faker $faker) {
    return [
        'box_number' => $faker->name,
        'part_name' => 0,
        'part_id' => $faker->uuid,
        'uploaded_by' => $faker->uuid,
    ];
});
