<?php

use Illuminate\Database\Seeder;

class BoxTypeDefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('box_type_def')->insert(
            [
                [
                    'type'=> 'standard',
                    'description'=> 'Standard'
                ],
                [
                    'type'=> 'oversize',
                    'description'=> 'Oversize'
                ],
                [
                    'type'=> 'media',
                    'description'=> 'Media'
                ],
                [
                    'type'=> 'memorabilia',
                    'description'=> 'Memorabilia'
                ],
            ]
        );
    }
}
