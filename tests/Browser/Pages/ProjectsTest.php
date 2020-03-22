<?php

namespace Tests\Browser\Pages;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class ProjectsTest
 * @package Tests\Browser\Pages
 */
class ProjectsTest extends DuskTestCase
{
    protected $user;

    public function setUp(){
        parent::setUp();
        $this->user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function testProjectsNav() {
        $this->browse(function(Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('it.projects')
                ->assertPathIs('/it/projects')
                ->assertSee('Manage Project');
        });
    }

    public function tearDown(){
        Project::truncate();
        User::truncate();
        Company::truncate();
        parent::tearDown();
    }

   /*public function testProjectDelete(){
       factory(Project::class)->create(['company_id'=>$this->user->company_id, 'project_name' => 'deleteproject1',
           'project_owner_id' => $this->user->id]);
       $this->browse(function(Browser $browser){
           $browser->loginAs($this->user)
               ->visitRoute('it.projects')
               ->waitForText('deleteproject1')
               ->click('@neu-delete-row')
               ->waitForText('Are you sure to delete this data')
               ->click('.modal-footer > .btn-primary')
               ->waitForText('Delete Project Successful')
               ->assertDontSee('deleteproject1');

       });
   }*/

}
