<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Indexing\Box::class, function (Faker $faker) {
    return [
        'box_name' => $faker->name,
        'box_location_code' => 'abc-123-xyz',
        'company_id' => function() {
	        return factory(\NeubusSrm\Models\Org\Company::class)->create()->id;
        },
        'project_id' => function() {
	        return factory(\NeubusSrm\Models\Org\Project::class)->create()->id;
        },
        'has_pending_requests' => false,
        'created_by' => function() {
    	    return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
        },
        'updated_by' => function() {
	        return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
        },
        'box_status' => 'NEW',
        'box_type' => $faker->randomElement(['standard', 'oversize', 'media'])
    ];
});
