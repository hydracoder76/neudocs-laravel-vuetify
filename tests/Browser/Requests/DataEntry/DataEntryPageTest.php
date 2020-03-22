<?php
/**
 * User: aho
 * Date: 2019-02-20
 * Time: 13:47
 */

namespace Tests\Browser\Requests\DataEntry;


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
class DataEntryPageTest extends DuskTestCase
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

    /**
     * @return mixed
     */
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


    /**
     * @throws \Throwable
     */
    public function testNavigate(){

        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->pause(500)
                ->clickLink('Data Entry')
                ->assertRouteIs('dataentry.home');

        });
    }


    /**
     * @throws \Throwable
     */
    public function testSelectedProjectDisplaysTable() {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->assertInputValue('#single-select', $project->project_name);
        });
    }

    /**
     * @throws \Throwable
     */
    public function testDataEntryTableContent(){
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $self = $this;
        $this->browse(function (Browser $browser) use ($user, $self,$project){
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->pause(500)
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-table')
                ->with('@neu-table', function ($table) {
                    $table->assertSee('Box Number');
                    $table->assertSee('Box Type');
                    $table->assertSee('Location');
                    $table->assertSee('RFID');
                    $table->assertSee('Created On');
                    $table->assertSee('Created By');
                    $table->assertSee('Last Updated By');
                    $table->assertSee('Part Count');
                    $table->assertSee('Deleted');
                });
        });
    }

    /**
     * @throws \Throwable
     */
    public function testAddBoxBtnPresent() {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-table')
                ->assertPresent('@add-box-button');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeAddBoxModal() {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->click('@add-box-button')
                ->waitFor('@neu-dataentry-box-modal')
                ->assertPresent('h5.modal-title');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testBoxTypeOnBoxModal() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->click('@add-box-button')
                ->waitFor('@neu-dataentry-box-modal')
                ->assertPresent('h5.modal-title')
                ->with('@neu-dataentry-box-modal', function ($modal) {
                    $modal->type('@box_name', 'box_name')
                        ->type('@box_location_code', 'box_location_code')
                        ->type('@rfid', 'rfid')
                        ->select('@box_tye', 'Oversize')
                        ->click('@neu-btn');
                })
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertSee('Oversize');

                });

        });
    }


    /**
     * @throws \Throwable
     */
    public function testCanShowTheFirstSelectionForNoDefaultProjectOnDataEntryPage() :void {
        $company = factory(Company::class)->create();
        $project = factory(Project::class)->create(['company_id'=>$company->id, 'project_name' => 'projectname1', 'project_description' => 'projectdesc1',
            'project_owner_id' => $this->user->id]);
        $project2 = factory(Project::class)->create(['company_id'=>$company->id, 'project_name' => 'projectname2', 'project_description' => 'projectdesc3',
            'project_owner_id' => $this->user->id]);
        $project3 = factory(Project::class)->create(['company_id'=>$company->id, 'project_name' => 'projectname3', 'project_description' => 'projectdesc3',
            'project_owner_id' => $this->user->id]);
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $firstProjectName = $project->project_name . '-' . $project->project_description;
        $secondProjectName = $project2->project_name . '-' . $project2->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $firstProjectName, $secondProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(2000)
                ->assertInputValue('#single-select', $firstProjectName)
                ->assertInputValueIsNot('#single-select', 'not first project name')
                ->type('#single-select', $secondProjectName)
                ->keys('#single-select',['{enter}'])
                ->assertInputValue('#single-select', $secondProjectName)
                ->assertInputValueIsNot('#single-select', 'not second project name')
                ->type('#single-select', '')
                ->click('@neu-dataentry-form')
                ->assertInputValue('#single-select', '')
            ;


        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnDataEntryPageOnIT() :void {
        $company = factory(Company::class)->create();
        $project = factory(Project::class)->create(['company_id'=>$company->id, 'project_name' => 'defaultprojectname', 'project_description' => 'defaultprojectdesc',
            'project_owner_id' => $this->user->id]);
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $defaultProjectName = $project->project_name . '-' . $project->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(2000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnDataEntryPageOnClient() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $user = User::whereRole(User::ROLE_CLIENT)->first();
        $defaultProjectName = $project->project_name . '-' . $project->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(2000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnDataEntryPageOnNeubus() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $user = User::whereRole(User::ROLE_NEUBUS)->first();
        $defaultProjectName = $project->project_name . '-' . $project->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(2000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnDataEntryPageOnAdmin() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $defaultProjectName = $project->project_name . '-' . $project->project_description;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(2000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testShowDefaultProjectOnDataEntryPageOnIt() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);

        $defaultProjectName = $project->project_name . '-' . $project->project_description;
        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testDataEntryPartAddPartSubmitByEnterClick() : void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        factory(IndexType::class, 1)->create(['project_id'=>$project->id, 'created_by' => $user->id]);
        $this->browse(function (Browser $browser) use ($user, $project){
            $browser->loginAs($user)
                ->visitRoute('dataentry.home')
                ->pause(500)
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-table')
                ->with('@neu-table', function ($table) {
                    $table->assertSee('Box Number');
                    $table->assertSee('Box Type');
                    $table->assertSee('Location');
                    $table->assertSee('RFID');
                    $table->assertSee('Created On');
                    $table->assertSee('Created By');
                    $table->assertSee('Last Updated By');
                    $table->assertSee('Part Count');
                    $table->click('tr:nth-child(1) > td:nth-child(1)');
                })
                ->pause(500)
                ->assertPathIs('/requests/dataentry/part')
                ->assertDontSee('Cancel')
                ->assertPresent('@add-part-button')
                ->assertPresent('@neu-dataentry-part-modal')
                ->click('@add-part-button')
                ->pause(10000)
                ->with('@neu-dataentry-part-modal', function ($modal) {
                    $modal->assertSee('Cancel');
                    $modal->type('input.neu-input.neu-input-lg', 'aaa');
                    $modal->keys('input.neu-input.neu-input-lg', ['{enter}']);
                })
                ->waitFor('@statusCRUD',5)
                ->assertSeeIn('@statusCRUD', 'part index added')
                ->assertDontSee('Cancel');
        });
    }
}
