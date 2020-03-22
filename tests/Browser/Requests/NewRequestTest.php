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
 * Class NewRequestTest
 * @package Tests\Browser\Pages
 */
class NewRequestTest extends DuskTestCase
{
    /**
     * @var
     */
    protected $user;

    /**
     * @returns void
     */
    public function setUp(): void {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->data();
    }

    /**
     * @returns void
     */
    public function data(): void {
        $project = factory(Project::class)->create(['company_id' => $this->user->company_id,
            'project_name' => 'projectname', 'project_owner_id' => $this->user->id]);
        $indexType = factory(IndexType::class)->create(['project_id' => $project->id,
            'created_by' => $this->user->id, 'index_label' => 'Label', 'index_internal_name' => 'label']);
        $box = factory(Box::class)->create(['company_id' => $project->company_id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'updated_by' => $this->user->id, 'box_name' => 'boxname']);
        $part1 = factory(Part::class)->create(['box_id' => $box->id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'last_updated_by' => $this->user->id, 'part_name' => 'part1']);
        $part2 = factory(Part::class)->create(['box_id' => $box->id, 'project_id' => $project->id,
            'created_by' => $this->user->id, 'last_updated_by' => $this->user->id, 'part_name' => 'part2']);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType->id,
            'box_id' => $box->id, 'part_index_value' => 'xxx']);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType->id,
            'box_id' => $box->id, 'part_index_value' => 'yyy123']);
    }

    /**
     * @returns void
     */
    public function deleteData(): void {
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
    public function tearDown(): void {
        $this->deleteData();
        parent::tearDown();
    }


    /**
     * @throws Throwable
     * @returns void
     */
    public function testNewRequestNav(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->assertPathIs('/requests/new/projectname')
                ->waitForText('Label')
                ->assertSee('Label')
                ->assertSee('Clear All')
                ->assertSee('Search')
                ->assertSee('Back');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testNavToNewRequest(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests')
                ->waitFor('@neu-dropdown')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select', ['{enter}'])
                ->waitForText('Download', 40)
                ->click('@neu-btn')
                ->waitForText('Back')
                ->assertPathIs('/requests/new/projectname')
                ->assertSee('Back');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testNewRequestSearch(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->assertSee('xxx')
                ->assertDontSee('yyy')
                ->assertSee('Select Parts')
                ->assertSee('Box Name');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testNewRequestSearchFail(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'zzz')
                ->click('@request-search')
                ->waitForText('Select', 40)
                ->assertDontSee('xxx')
                ->assertDontSee('yyy')
                ->assertDontSee('boxname');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testCreateNewRequest(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->check('@select-check')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->assertSeeIn('td', 'part1')
                ->assertSee('boxname')
                ->assertSee('xxx')
                ->click('@request-create')
                ->waitForText('New Request')
                ->assertSee('New Request')
                ->assertSee('projectname');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testAddMultiple(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->check('@select-check')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->assertSeeIn('td', 'part1')
                ->assertSee('boxname')
                ->assertSee('xxx')
                ->click('@request-select-back')
                ->waitForText('Label')
                ->type('#index_label', 'yyy123')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->check('@select-check')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->assertSeeIn('td', 'part1')
                ->assertSee('part2')
                ->assertSee('boxname')
                ->assertSee('xxx')
                ->assertSee('yyy123');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testDelete(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->check('@select-check')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->click('.neu-delete-icon')
                ->pause(500)
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select',['{enter}'])
                ->waitForText('Not ready', 40)
                ->assertSee('xxx');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testCreateNewRequestFail(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'zzz')
                ->click('@request-search')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->click('@request-create')
                ->waitForText('Need at least one part')
                ->assertSeeIn('.alert-warning', 'Need at least one part')
                ->click('@request-back')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', 'projectname')
                ->keys('#single-select', ['{enter}'])
                ->waitForText('Download', 40)
                ->assertDontSee('Not ready');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testAlertWhenBack(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'xxx')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->check('@select-check')
                ->click('@request-review')
                ->waitForText('Review Selected Parts')
                ->click('@request-back')
                ->waitForText('There are unstaged changes')
                ->assertSee('There are unstaged changes')
                ->assertSee('Are you sure you want to go back to the Requests page?');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testAutoSearch(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'yyy')
                ->waitFor('li', 10)
                ->assertSeeIn('li', 'yyy123')
                ->click('li')
                ->pause(500)
                ->assertInputValue('#index_label', 'yyy123')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->assertSee('yyy123')
                ->assertDontSee('xxx')
                ->assertSee('Select Parts')
                ->assertSee('Box Name')
                ->assertSee( 'part2');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testAutoAfterSearch() : void{
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('requests.new', 'projectname')
                ->waitForText('Label')
                ->type('#index_label', 'yyy')
                ->waitFor('li', 10)
                ->assertSeeIn('li', 'yyy123')
                ->type('#index_label', 'zzz')
                ->waitUntilMissing('li')
                ->assertDontSee('yyy')
                ->type('#index_label', 'yyy123')
                ->click('@request-search')
                ->waitForText('boxname', 40)
                ->type('#index_label', 'xxx')
                ->waitFor('li', 10)
                ->assertSeeIn('li', 'xxx')
                ->type('#index_label', 'zzz')
                ->waitUntilMissing('li')
                ->assertDontSee('xxx')
                ->assertMissing('li');
        });
    }
}