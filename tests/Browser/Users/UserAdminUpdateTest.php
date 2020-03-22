<?php

namespace Tests\Browser\Users;

use NeubusSrm\Models\Org\Company;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;

/**
 * Class UserAdminUpdateTest
 * @package Tests\Browser\Users
 */
class UserAdminUpdateTest extends DuskTestCase
{
    protected $user,$userLogin,$project;

    public function setUp() {
        parent::setUp();
        $this->project = factory(Project::class)->create();
        $this->userLogin = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $this->project->company_id
        ]);
        $this->user = factory(User::class)->create();
    }

    public function tearDown() {
        Project::truncate();
        User::truncate();
        Company::truncate();
        unset($this->user);
        parent::tearDown();
    }



    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCanSeeUpdateModal()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = $this->project;
        $user = $this->userLogin;


        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visit('/client/users')
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertSee($this->userLogin->email)
                        ->click('@neu-edit-row');
                })
                ->assertVisible('@modalEdit');
        });
    }

    public function testCanUpdateUser()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = $this->project;
        $user = $this->userLogin;


        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visit('/client/users')
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertSee($this->userLogin->email)
                        ->click('@neu-edit-row');
                })
                ->with('@modalEdit', function ($modal) {
                    $modal ->type('@name', $this->user->name)
                        ->type('@email', $this->user->email)
                        ->type('@password', $this->user->password)
                        ->waitFor('@company_id')
                        ->select('@company_id', $this->project->name)
                        ->waitFor('@role')
                        ->select('@role', 'admin')
                        ->press('OK');
                        })
                ->waitFor('@statusCRUD',10)
                ->assertSeeIn('@statusCRUD', "User edited succesfully. ")
                ->with('@neu-table', function ($table) {
                    $table->assertSee($this->user->email);

                });
        });
    }

    public function testCanUpdateUserWithoutPass()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = $this->project;
        $user = $this->userLogin;
        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visit('/client/users')
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertSee($this->userLogin->email)
                        ->click('@neu-edit-row');
                })
                ->with('@modalEdit', function ($modal) {
                    $modal ->type('@name', $this->user->name)
                        ->type('@email', $this->user->email)
                        ->press('OK');
                })
                ->waitFor('@statusCRUD',10)
                ->assertSeeIn('@statusCRUD', "User edited succesfully. ")
                ->with('@neu-table', function ($table) {
                    $table->assertSee($this->user->email);

                });
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testResetUserPass(){
        $itUser = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $this->project->company_id,
            'email' => 'it@neuone.com',
            'password' => bcrypt('password')
        ]);
        $this->browse(function (Browser $browser) use ($itUser) {
            $browser->loginAs($itUser)
                ->visit('/admin/users')
                ->waitForText('it@neuone.com')
                ->assertSee('Reset Password')
                ->assertSeeIn('@neu-reset-pass-' . $itUser->id, 'Reset')
                ->click('@neu-reset-pass-' . $itUser->id)
                ->waitForText('Reset password successful')
                ->assertSee('Reset password successful')
                ->clickLink('Logout')
                ->waitForText('Please login below')
                ->type('@email', 'it@neuone.com')
                ->type('@password', 'password')
                ->click('@neu-login-btn')
                ->waitForText('Generic login error')
                ->assertSee('Generic login error');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testCantResetPass(){
        $itUser = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $this->project->company_id,
            'email' => 'it@neuone.com'
        ]);
        $neubusUser = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'company_id' => $this->project->company_id,
            'email' => 'neubus@neuone.com'
        ]);
        $diffUser = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'diff@neuone.com'
        ]);
        $this->browse(function (Browser $browser) use ($itUser, $neubusUser, $diffUser){
            $browser->loginAs($this->userLogin)
                ->visit('/admin/users')
                ->waitForText('it@neuone.com')
                ->assertSee('it@neuone.com')
                ->assertSee('neubus@neuone.com')
                ->assertSee('diff@neuone.com')
                ->assertPresent('@neu-reset-pass-' . $this->userLogin->id)
                ->assertMissing('@neu-reset-pass-' . $itUser->id)
                ->assertMissing('@neu-reset-pass-' . $neubusUser->id)
                ->assertMissing('@neu-reset-pass-' . $diffUser->id);
        });
    }

}
