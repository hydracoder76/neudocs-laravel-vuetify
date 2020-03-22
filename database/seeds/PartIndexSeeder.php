<?php

use Illuminate\Database\Seeder;

class PartIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\NeubusSrm\Models\Indexing\PartIndex::class,1)->create();
    }
}
