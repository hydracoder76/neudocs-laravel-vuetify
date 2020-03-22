<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\FileType::class, function (Faker $faker) {
    return [
        'file_type_name' => $faker->name,
        'file_type_ext' => 'png',
        'file_type_full_mime' => $faker->mimeType,
        'project_schema_id' => $faker->randomNumber(),
    ];
});
