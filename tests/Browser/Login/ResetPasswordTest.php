<?php

namespace Tests\Browser\Login;


use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Company;
use Tests\DuskTestCase;

class ResetPasswordTest extends DuskTestCase
{
    /**
     * @var User $newUser
     */
    protected $newUser;

    /**
     * @returns void
     */
    public function setUp(): void {
        parent::setUp();
        $this->data();
    }

    /**
     * @returns void
     */
    public function data(): void {
        $this->newUser = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'is_temp' => true,
            'name' => 'user2',
            'email' => 'email2@neuone.com',
            'password' => bcrypt('password')
        ]);
        $this->newUser->has_mfa = false;
        $this->newUser->save();
        factory(Project::class)->create(['company_id' => $this->newUser->company_id,
            'project_name' => 'projectname', 'project_owner_id' => $this->newUser->id]);

    }

    /**
     * @returns void
     */
    public function deleteData(): void {
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
    public function testNewUserRedirectReset(): void{
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->newUser)
                ->visitRoute('reset.password')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password')
                ->assertPathIs('/resetPassword')
                ->assertSee('Reset Password')
                ->assertSee('Enter new password')
                ->assertSee('Enter new password again')
                ->assertVisible('@neu-btn');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testNewUserRedirectOther(): void {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->newUser)
                ->visitRoute('admin.users')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password')
                ->visitRoute('it.home')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password')
                ->visitRoute('it.projects')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password')
                ->visitRoute('it.companies')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password')
                ->visitRoute('requests')
                ->waitForText('Enter new password')
                ->assertRouteIs('reset.password');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testError(): void{
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->newUser)
                ->visitRoute('reset.password')
                ->waitForText('Enter new password')
                ->click('@neu-btn')
                ->waitForText('The given data was invalid')
                ->assertSee('The given data was invalid')
                ->type('#passwordInput', '*New23Password')
                ->type('#passwordInput2', '#New22Password')
                ->click('@neu-btn')
                ->waitForText('Passwords must match')
                ->assertSee('Passwords must match');
        });
    }

    /**
     * @throws Throwable
     * @returns void
     */
    public function testNewUserReset(): void{
        $newUserArr = [User::ROLE_IT, User::ROLE_ADMIN, User::ROLE_NEUBUS, User::ROLE_CLIENT];
        foreach($newUserArr as $key => $newUserRole) {
            $this->newUser->role = $newUserRole;
            $this->newUser->is_temp = true;
            $this->newUser->save();
            $this->browse(function (Browser $browser){
                $browser->loginAs($this->newUser)
                    ->visitRoute('reset.password')
                    ->waitForText('Enter new password')
                    ->type('#passwordInput', '*New22Password')
                    ->type('#passwordInput2', '*New22Password')
                    ->assertVisible('@neu-btn')
                    ->click('@neu-btn')
                    ->waitForText('Reset password successful')
                    ->assertSee('Reset password successful')
                    ->logout()
                    ->loginAs($this->newUser)
                    ->visitRoute('reset.password')
                    ->assertDontSee('Enter new password')
                    ->assertSee('403');
            });
        }
    }




}
