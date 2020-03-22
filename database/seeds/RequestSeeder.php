<?php

use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \NeubusSrm\Models\Auth\User::first();
        //1 for each part
        \NeubusSrm\Models\Indexing\Part::all()->each(function($part){
            $request = factory(\NeubusSrm\Models\Org\Request::class)->create(['company_id'=>$part->project->company_id,
                 'project_id'=>$part->project_id, 'is_fulfilled' => false, 'is_in_process' => false]);
            $request->parts()->attach($part->id);
        });
        //completed parts
        //assume each box has 3 parts
        \NeubusSrm\Models\Indexing\Box::all()->each(function($box) use ($user){
            $request = factory(\NeubusSrm\Models\Org\Request::class)->create(['company_id'=>$box->company_id,
                'project_id'=>$box->project_id, 'is_fulfilled' => true, 'is_in_process' => false, 'fulfilled_by'=>$user->id]);
            foreach($box->parts as $part){
                $indexes = '';
                foreach($part->indexes as $index){
                    $indexes .= $index->indexType->index_label . ' ' . $index->part_index_value . ' ';
                }
                $text = $box->project->project_name . ' ' . $request->request_name . ' ' . $indexes . ' ' . $box->box_location_code .
                    $box->box_name . ' ' . $part->part_name . ' ' . $user->name;
                $text = strtolower($text);
                $requestPart = factory(\NeubusSrm\Models\Relational\RequestPart::class)->create(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id]);
                DB::table('request_parts')->where(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id])->update(['searchtext'=>$text]);
                $filename = $part->id . '_file.pdf';
                $location = config('srm.file_storage.local.dir') . 'temp/';
                if (!file_exists($location))
                    mkdir($location, 777);
                $trueLocation = $location . $filename;
                file_put_contents($trueLocation, $filename);
                factory(\NeubusSrm\Models\Indexing\FileUpload::class)->create(['box_number'=>$box->box_name, 'part_name'=>$part->part_name,
                    'part_id'=>$part->id,'uploaded_by'=>$user->id,'true_file_name'=>$filename,'hashed_file_name'=>$filename,'file_mime'=>'pdf',
                    'current_fs_location'=>$trueLocation]);
            }
        });
    }
}
