<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Relational\RequestPart::class, function (Faker $faker) {
    return [
        'part_id_ref' => function() {
            factory(\NeubusSrm\Models\Indexing\Part::class)->create()->id;
        },
        'request_id_ref' => function() {
            factory(\NeubusSrm\Models\Org\Request::class)->create()->id;
        },
    ];
});
