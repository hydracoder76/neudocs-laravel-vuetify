<?php

use Illuminate\Database\Seeder;

/**
 * Class BoxSeeder
 */
class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\NeubusSrm\Models\Indexing\Box::class)->create();
    }
}
