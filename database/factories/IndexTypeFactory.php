<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\IndexType::class, function (Faker $faker) {
    $name = $faker->name;
	return [
		'index_internal_name' => str_replace(' ', '_', strtolower($name)).'_'.uniqid(md5(time())),
        'index_label' => $name,
		'has_validation' => $faker->boolean,
		'validation_regex' => $faker->regexify('validationregex'),
		'validation_rule_class_name' => ucfirst($faker->word),
		'created_by' => function () {
			return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
		},
		'project_id' => $faker->uuid
	];
});