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
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
/**
 * Class ScanPageTest
 * @package Tests\Browser\Requests\Todo
 */
class ScanPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testNavigate(){
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create(['project_name' => 'name']);
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->pause(500)
                ->keys('#single-select', 'name','{enter}')
                ->waitFor('#neu-todo-scan-btn')
                ->click('#neu-todo-scan-btn > button')
                ->assertRouteIs('todo.scan')
                ->click('#cancel-btn > button')
                ->assertRouteIs('todo.home');

        });
    }

    public function testButtonPresentText(){
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('todo.scan')
                ->pause(500)
                ->assertPresent('#scan-btn')
                ->assertPresent('#upload-btn')
                ->assertPresent('#cancel-btn')
                ->assertVue('btnText', 'Start Scanning', '#scan-btn')
                ->assertVue('btnText', 'Upload', '#upload-btn')
                ->assertVue('btnText', 'Cancel', '#cancel-btn');
        });
    }

    public function testOpenModal(){
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('todo.scan')
                ->pause(500)
                ->click('#options-btn-0 > button')
                ->pause(500)
                ->assertVisible('@deleted-check')
                ->assertVisible('@first-check')
                ->assertVisible('@project-select');
        });
    }

    public function testModalContent(){
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
                ->visitRoute('todo.scan')
                ->pause(500)
                ->click('#options-btn-0 > button')
                ->waitFor('@deleted-check')
                ->check('@deleted-check')
                ->click('.btn-primary')
                ->driver->executeScript("document.getElementById('scanScroll').scrollTop = 700");
                $browser->pause(500)
                ->click('#options-btn-1 > button')
                    ->waitFor('@deleted-check')
                ->check('@first-check')
                ->click('.btn-primary')
                ->driver->executeScript("document.getElementById('scanScroll').scrollTop = 0");
                $browser->pause(500)
                ->click('#options-btn-0 > button')
                ->waitFor('@deleted-check')
                ->assertChecked('#deleted-check')
                ->assertNotChecked('#first-check')
                ->click('.btn-secondary')
                ->driver->executeScript("document.getElementById('scanScroll').scrollTop = 700");
                $browser->pause(500)
                ->click('#options-btn-1 > button')
                ->waitFor('@deleted-check')
                ->assertChecked('#first-check')
                ->assertNotChecked('#deleted-check');
        });
    }

}