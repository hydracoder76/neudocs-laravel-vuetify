<?php

use Illuminate\Database\Seeder;

/**
 * Class SrmLogSeeder
 */
class SrmLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return factory(\NeubusSrm\Models\Util\SrmLog::class)->create();
    }
}
