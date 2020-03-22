<?php

use Illuminate\Database\Seeder;

/**
 * Class SRMSeeder
 */
class SRMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = factory(\NeubusSrm\Models\Org\Company::class)->create();
        $user = factory(\NeubusSrm\Models\Auth\User::class)->create(['company_id'=>$company->id, 'role' => 'it',]);
        $user->has_mfa = false;
        $user->save();
        $projects = factory(\NeubusSrm\Models\Org\Project::class, 3)->create(['company_id'=>$company->id]);
        foreach ($projects as $project) {
            factory(\NeubusSrm\Models\Org\Setting::class)->create(['project_id'=>$project->id, 'setting_key'=>'priority_yellow','value'=>'10',
                'label'=>'Days until yellow request']);
            factory(\NeubusSrm\Models\Org\Setting::class)->create(['project_id'=>$project->id, 'setting_key'=>'priority_red','value'=>'14',
                'label'=>'Days until red request']);
            $indexTypes = factory(\NeubusSrm\Models\Indexing\IndexType::class, 3)->create([
                'project_id' => $project->id,
                'created_by' => $user->id
            ]);
            $boxes = factory(\NeubusSrm\Models\Indexing\Box::class, 3)->create([
                'company_id' => $project->company_id,
                'project_id' => $project->id,
                'created_by' => $user->id,
                'updated_by' => $user->id
            ]);
            foreach ($boxes as $box) {
                $parts = factory(\NeubusSrm\Models\Indexing\Part::class, 3)->create([
                    'box_id' => $box->id,
                    'project_id' => $project->id,
                    'created_by' => $user->id,
                    'last_updated_by' => $user->id
                ]);
                foreach ($parts as $key => $part) {
                    $part->part_name = $key + 1;
                    $part->save();
                    foreach ($indexTypes as $indexType) {
                        DB::table('part_indexes')->insert([
                            'part_id' => $part->id,
                            'index_type_id' => $indexType->id,
                            'box_id' => $box->id,
                            'part_index_value' => chr(rand(97, 122))
                        ]);
                        /*$partIndex = factory(\NeubusSrm\Models\Indexing\PartIndex::class)->create([
                            'part_id' => $part->id,'index_type_id'=>$indexType->id, 'box_id'=>$box->id
                        ]);*/
                    }
                }
            }
        }
    }
}
