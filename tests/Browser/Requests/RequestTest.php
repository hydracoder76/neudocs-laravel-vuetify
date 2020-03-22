<?php

namespace Tests\Browser\Requests;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Indexing\PartIndex;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use DB;

/**
 * Class RequestTest
 * @package Tests\Browser\Pages
 */
class RequestTest extends DuskTestCase
{

    /**
     * @var
     */
    protected $user;

    /**
     * @returns void
     */
    public function setUp() : void{
        parent::setUp();
        $this->user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);
        $this->data();
    }

    /**
     * @returns void
     */
    public function data() : void{
        $project = factory(Project::class)->create(['company_id'=>$this->user->company_id, 'project_name' => 'projectname',
            'project_owner_id' => $this->user->id]);
        $indexType = factory(IndexType::class)->create(['project_id' => $project->id,
            'created_by' => $this->user->id, 'index_label' => 'Label', 'index_internal_name' => 'label']);
        $box = factory(Box::class)->create(['company_id' => $project->company_id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'updated_by' => $this->user->id,]);
        $part1 = factory(Part::class)->create(['box_id' => $box->id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'last_updated_by' => $this->user->id,]);
        $part2 = factory(Part::class)->create(['box_id' => $box->id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'last_updated_by' => $this->user->id,]);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType->id,
            'box_id' => $box->id, 'part_index_value' => 'xxx']);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType->id,
            'box_id' => $box->id, 'part_index_value' => 'yyy']);
        $request1 = factory(Request::class)->create(['company_id'=>$this->user->company_id,
            'project_id'=>$project->id, 'is_fulfilled' => false, 'is_in_process' => false]);
        $request1->parts()->attach($part1->id);
        $request2 = factory(Request::class)->create(['company_id'=>$this->user->company_id,
            'project_id'=>$project->id, 'is_fulfilled' => false, 'is_in_process' => false]);
        $request2->parts()->attach($part2->id);
    }

    /**
     * @returns void
     */
   public function deleteData() : void{
        RequestPart::truncate();
        Request::truncate();
        PartIndex::truncate();
        Part::truncate();
        Box::truncate();
        IndexType::truncate();
        Project::truncate();
        User::truncate();
        Company::truncate();
    }

    /**
     * @returns void
     */
    public function tearDown() : void{
        $this->deleteData();
        parent::tearDown();
    }


    /**
     * @throws Throwable
     * @returns void
     */
    public function testRequestNav() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->assertPathIs('/requests')
                ->assertSee('Request')
                ->assertPresent('@panel-header');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testSelectProject() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select',['{enter}'])
                ->waitForText('Not ready', 40)
                ->assertSee('xxx')
                ->assertSee('yyy')
                ->assertSee('New')
                ->assertSee('Not ready')
                ->assertSee('New Request')
                ->assertSee('Search');
        });
    }


    /**
     * @throws Throwable
     * @returns void
     */
    public function testSelectProjectFail() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'zzz')
                ->keys('#single-select',['{enter}'])
                ->assertDontSee('xxx')
                ->assertDontSee('yyy')
                ->assertDontSee('Not ready');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testSearchRequest() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select',['{enter}'])
                ->waitForText('Not ready', 40)
                ->click('@request-filter')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->pause(500)
                ->assertSee('xxx')
                ->assertDontSee('yyy')
                ->assertSee('New')
                ->assertSee('Not ready');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testSearchRequestNone() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select',['{enter}'])
                ->waitForText('Not ready', 40)
                ->click('@request-filter')
                ->waitForText('Label')
                ->type('#index_label', '')
                ->click('@request-search')
                ->pause(500)
                ->assertSee('xxx')
                ->assertSee('yyy')
                ->assertSee('New')
                ->assertSee('Not ready');;
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testSearchRequestFail() : void{
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select',['{enter}'])
                ->waitForText('Not ready', 40)
                ->click('@request-filter')
                ->waitForText('Label')
                ->type('#index_label', 'zzz')
                ->click('@request-search')
                ->pause(500)
                ->assertDontSee('xxx')
                ->assertDontSee('yyy')
                ->assertDontSee('Not ready');;
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnRequestsPageOnIt() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);

        $defaultProjectName = $project->project_name;
        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnRequestsPageOnClient() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $defaultProjectName = $project->project_name;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnRequestsPageOnAdmin() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);
        $defaultProjectName = $project->project_name;

        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }
}
