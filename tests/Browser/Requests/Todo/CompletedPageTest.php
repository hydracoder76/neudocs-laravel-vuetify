<?php
/**
 * User: mlawson
 * Date: 2019-01-14
 * Time: 13:47
 */

namespace Tests\Browser\Requests\Todo;


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
 * Class CompletedPageTest
 * @package Tests\Browser\Requests\Todo
 */
class CompletedPageTest extends DuskTestCase
{



    protected $user;
    protected $company;

    public function setUp() {
        parent::setUp();
        $this->user = $this->company();
        $this->requests();
    }
    public function tearDown(){
        RequestPart::truncate();
        Request::truncate();
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

    public function requests(){
        $user = $this->user;
        Box::all()->each(function($box) use ($user){
            $request = factory(Request::class)->create(['company_id'=>$box->company_id,
                'project_id'=>$box->project_id, 'is_fulfilled' => true, 'is_in_process' => false, 'fulfilled_by'=>$user->id]);
            foreach($box->parts as $part){
                $indexes = '';
                foreach($part->indexes as $index){
                    $indexes .= $index->indexType->index_label . ' ' . $index->part_index_value . ' ';
                }
                $text = $box->project->project_name . ' ' . $request->request_name . ' ' . $indexes . ' ' . $box->box_location_code .
                    $box->box_name . ' ' . $part->part_name;
                $requestPart = factory(RequestPart::class)->create(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id]);
                \DB::table('request_parts')->where(['request_id_ref'=>$request->id, 'part_id_ref'=>$part->id])->update(['searchtext'=>$text]);}
        });
    }

    public function testContent(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $self = $this;
        $this->browse(function (Browser $browser) use ($user, $self){
            $browser->loginAs($user)
                ->visitRoute('todo.completed')
                ->pause(500);
            $temp = $browser->text('th:nth-child(1)');
            $self->assertEquals($temp, 'Project');
            $temp = $browser->text('th:nth-child(2)');
            $self->assertEquals($temp, 'Request Name');
            $temp = $browser->text('th:nth-child(3)');
            $self->assertEquals($temp, 'Index');
            $temp = $browser->text('th:nth-child(4)');
            $self->assertEquals($temp, 'Location');
            $temp = $browser->text('th:nth-child(5)');
            $self->assertEquals($temp, 'Box Name');
            $temp = $browser->text('th:nth-child(6)');
            $self->assertEquals($temp, 'Part Name');
            $temp = $browser->text('th:nth-child(7)');
            $self->assertEquals($temp, 'Requested At');
            $temp = $browser->text('th:nth-child(8)');
            $self->assertEquals($temp, 'Completed At');
            $temp = $browser->text('th:nth-child(9)');
            $self->assertEquals($temp, 'Completed By');
            $temp = $browser->text('th:nth-child(10)');
            $self->assertEquals($temp, 'Download');

        });
    }

    public function testNavigate(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->pause(500)
                ->clickLink('Request Completed')
                ->assertRouteIs('todo.completed');

        });
    }

    public function testSearch(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $text = substr(Request::where('is_fulfilled', true)->first()->request_name, 0, 6);
        $this->browse(function (Browser $browser) use ($user, $text){
            $browser->loginAs($user)
                ->visitRoute('todo.completed')
                ->waitFor('@neu-filter-input')
                ->type('@neu-filter-input', $text)
                ->assertSeeIn(".table", $text);
        });
    }

    public function testList(){
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $this->company->id
        ]);
        $requestPart = RequestPart::whereHas('request', function($query){
            $query->where('is_fulfilled', 'true');
        })->orderBy('request_id_ref')->orderBy('created_at', 'DESC')->first();
        $projectName = $requestPart->part->project->project_name;
        $partName = $requestPart->part->part_name;
        $this->browse(function (Browser $browser) use ($user, $projectName, $partName){
            $browser->loginAs($user)
                ->visitRoute('todo.completed')
                ->waitForText($projectName)
                ->assertSeeIn(".table", $projectName)
                ->assertSeeIn(".table", $partName);
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnRequestsToDoPageOnIt() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);

        $defaultProjectName = $project->project_name . '-' . $project->project_description;
        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnRequestsToDoPageOnNeubus() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $defaultProjectName = $project->project_name . '-' . $project->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

}
    public function testDataEntryCompletedRequestsTableContent(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $self = $this;
        $this->browse(function (Browser $browser) use ($user, $self,$project){
            $browser->loginAs($user)
                ->visitRoute('todo.completed')
                ->pause(500)
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-table')
                ->with('@neu-table', function ($table) {
                    $table->assertSee('Project');
                    $table->assertSee('Request Name');
                    $table->assertSee('Index');
                    $table->assertSee('Location');
                    $table->assertSee('Box Name');
                    $table->assertSee('Part Name');
                    $table->assertSee('Requested At');
                    $table->assertSee('Completed At');
                    $table->assertSee('Completed By');
                    $table->assertSee('Download');
                });
        });
    }

}
