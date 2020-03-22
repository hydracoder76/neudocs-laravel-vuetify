<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\PartIndex::class, function (Faker $faker) {

	return [
		'part_id' => $faker->randomNumber(),
		'part_index_value' => $faker->randomAscii,
		'index_type_id' => function () {
			return factory(\NeubusSrm\Models\Indexing\IndexType::class)->create()->id;
		},
		'box_id' => function(){
            return factory(\NeubusSrm\Models\Indexing\Box::class)->create()->id;
        }
	];
});
