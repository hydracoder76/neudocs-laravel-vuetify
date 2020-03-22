<?php

use Illuminate\Database\Seeder;
use NeubusSrm\Models\Org\MediaType;

/**
 * Class MediaTypeSeeder
 */
class MediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       MediaType::insert(
            [
                [
                    'type'=> 'Paper',
                ],
                [
                    'type'=> 'Memorabilia',
                ],
                [
                    'type'=> 'Photo',
                ],
                [
                    'type'=> 'Audio',
                ],
                [
                    'type'=> 'Video',
                ],
            ]
        );
    }
}
