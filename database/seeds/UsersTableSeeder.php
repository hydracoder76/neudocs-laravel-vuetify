<?php

use Illuminate\Database\Seeder;


/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\NeubusSrm\Models\Auth\User::class)->create();
    }
}
