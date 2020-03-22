<?php

namespace Tests\Browser\Pages;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Setting;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

/**
 * Class SettingsTest
 * @package Tests\Browser\Pages
 */
class SettingsTest extends DuskTestCase
{
    protected $user;

    public function setUp(){
        parent::setUp();
        $this->user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);
        $project = factory(Project::class)->create(['company_id'=>$this->user->company_id, 'project_name' => 'downloadproject',
            'project_owner_id' => $this->user->id, 'project_name' => 'settingTest']);
        factory(Setting::class)->create(['project_id'=>$project->id, 'setting_key'=>'priority_yellow','value'=>'10',
            'label'=>'Days until yellow request']);
        factory(Setting::class)->create(['project_id'=>$project->id, 'setting_key'=>'priority_red','value'=>'14',
            'label'=>'Days until red request']);
    }


    public function testSettingNav() {
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('it.settings')
                ->assertPathIs('/it/settings')
                ->waitFor('@neu-dropdown')
                ->assertSee('Settings');
        });
    }

    public function testViewSetting(){
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('it.settings')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', 'settingTest')
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-btn')
                ->assertPresent('@neu-btn')
                ->assertSee('Days until yellow request')
                ->assertSee('Days until red request')
                ->assertValue('#0_setting', '10')
                ->assertValue('#1_setting', '14');
        });
    }

    public function testSetSetting(){
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('it.settings')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', 'settingTest')
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-btn')
                ->type('#0_setting', '22')
                ->type('#1_setting', '24')
                ->click('@neu-btn')
                ->waitForText('Saved')
                ->assertValue('#0_setting', '22')
                ->assertValue('#1_setting', '24');;
        });
    }

    public function testYellowGreaterRed(){
        $this->markTestIncomplete('Needs further refactoring before viable');
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->user)
                ->visitRoute('it.settings')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', 'settingTest')
                ->keys('#single-select',['{enter}'])
                ->waitFor('@neu-btn')
                ->type('#0_setting', '24')
                ->type('#1_setting', '22')
                ->click('@neu-btn')
                ->waitForText('Setting for yellow cannot be greater')
                ->assertSee('Setting for yellow cannot be greater');
        });
    }

    public function tearDown(){
        Setting::truncate();
        Project::truncate();
        User::truncate();
        Company::truncate();
        parent::tearDown();
    }

    /**
     * @throws \Throwable
     */
    public function testCanShowDefaultProjectOnSettingsPageOnIt() :void {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id,
            'default_project_id' => $project->id
        ]);

        $defaultProjectName = $project->project_name . '-' . $project->project_description;
        $this->browse(function (Browser $browser) use ($user, $project, $defaultProjectName) {
            $browser->loginAs($user)
                ->visitRoute('it.settings')
                ->waitFor('@neu-dropdown')
                ->pause(1000)
                ->assertInputValue('#single-select', $defaultProjectName)
                ->assertInputValueIsNot('#single-select', 'not default project name');
        });
    }
}
