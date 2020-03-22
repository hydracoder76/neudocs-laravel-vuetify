<?php

use Faker\Generator as Faker;

$factory->define(NeubusSrm\Models\Org\Project::class, function (Faker $faker) {
    return [
        'project_name' => $faker->name.'_'.uniqid(md5(time())),
        'project_description' => $faker->text,
        'project_owner_id' => $faker->uuid,
        'company_id' => function() {
    	    return factory(\NeubusSrm\Models\Org\Company::class)->create()->id;
        }
    ];
});
