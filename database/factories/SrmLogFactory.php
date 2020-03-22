<?php

use Faker\Generator as Faker;

$factory->define(\NeubusSrm\Models\Util\SrmLog::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\NeubusSrm\Models\Auth\User::class)->create()->id;
        },
        'company_id' => function() {
            return factory(\NeubusSrm\Models\Org\Company::class)->create()->id;
        },
        'record_id' => $faker->uuid,
        'operation' => $faker->randomElement(['create', 'retrieve', 'update', 'delete']),
        'level' => $faker->randomElement(['debug', 'info', 'warn', 'error']),
        'message' => $faker->sentence,
        'details' => json_encode([]),
        'ip_address' => $faker->ipv4,
        'responsible_table' => $faker->randomElement(['parts', 'boxes', 'requrests', 'users', 'projects', 'companies'])
    ];
});
