<?php

namespace Tests\Browser\Pages;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class CompanyTest
 * @package Tests\Browser\Pages
 */
class CompanyTest extends DuskTestCase
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
    public function testCompanyNav() {
        $this->browse(function(Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('it.companies')
                ->assertPathIs('/it/companies')
                ->assertSee('Company Configuration');
        });
    }

    public function tearDown(){
        Project::truncate();
        User::truncate();
        Company::truncate();
        parent::tearDown();
    }

}
