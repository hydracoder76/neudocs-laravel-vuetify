<?php
/**
 * User: aho
 * Date: 2019-02-20
 * Time: 13:47
 */

namespace Tests\Browser\Requests;


use Tests\DuskTestCase;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Index;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Models\Org\Request;
use Laravel\Dusk\Browser;
use DB;

/**
 * Class RequestPageTest
 * @package Tests\Browser\Requests
 */
class RequestPageTest extends DuskTestCase
{



    protected $user;
    protected $company;

    public function setUp() {
        parent::setUp();
        $this->user = $this->company();
    }
    public function tearDown(){
        PartIndex::truncate();
        Part::truncate();
        Box::truncate();
        IndexType::truncate();
        Project::truncate();
        User::truncate();
        Company::truncate();
        parent::tearDown();
    }

    public function company(){
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create(['company_id'=>$company->id, 'password' => bcrypt(parent::USER_PASSWORD)]);
        $projects = factory(Project::class, 3)->create(['company_id'=>$company->id]);
        foreach ($projects as $project){
            $indexTypes = factory(IndexType::class, 3)->create(['project_id'=>$project->id, 'created_by' => $user->id]);
            $box = factory(Box::class)->create(['company_id'=>$project->company_id, 'project_id'=>$project->id,
                'created_by'=> $user->id, 'updated_by'=>$user->id]);
            $parts = factory(Part::class, 3)->create(['box_id'=>$box->id,'project_id'=>$project->id,
                'created_by'=> $user->id, 'last_updated_by'=>$user->id]);
            foreach($parts as $part){
                foreach($indexTypes as $indexType){
                    DB::table('part_indexes')->insert([
                        'part_id' => $part->id,
                        'index_type_id' => $indexType->id,
                        'box_id' => $box->id,
                        'part_index_value' => chr(rand(97, 122))
                    ]);
                }
            }
        }
        $this->company = $company;
        return $user;
    }

    public function testNavigate(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user,$project){
            $browser->loginAs($user)
                ->visitRoute('requests',['project'=> $project->project_name])
                ->pause(500)
                ->assertSee("Request");

        });
    }

    public function testSelectedProjectDisplaysTable() {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('requests',['project'=> $project->project_name])
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->assertInputValue('#single-select', $project->project_name);
        });
    }


}