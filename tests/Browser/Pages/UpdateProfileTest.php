<?php

namespace Tests\Browser\Pages;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;

/**
 * Class UpdateProfileTest
 * @package Tests\Browser\Pages
 */
class UpdateProfileTest extends DuskTestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Project
     */
    protected $project;

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
        $project = factory(Project::class)->create([
            'company_id'=>$this->user->company_id,
            'project_name' => 'projectname',
            'project_owner_id' => $this->user->id
        ]);

        $this->project = $project;
    }

    /**
     * @returns void
     */
    public function deleteData() : void{
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
     * @throws \Throwable
     */
    public function testCanSeeUpdateUserProfilePageAfterLoginAndOnClient() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertPathIs('/profile/update')
                ->assertSeeIn('@neu-heading','Update Profile')
                ->assertSee( 'Name')
                ->assertSee( 'Email')
                ->assertSee( 'Password')
                ->assertDontSee( 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckDefaultUserDataOnClient() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->assertInputValueIsNot('@name', 'not default user name')
                ->assertInputValue('@email', $user->email)
                ->assertInputValueIsNot('@email', 'not default user email');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckUpdateUserNameOnClient() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = 'Client Client';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->assertInputValue('@name', $testUserName)
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserLogoutAfterChangingEmailOnClient() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserEmail = 'example@gmail.com';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@email', $user->email)
                ->value('@email','')
                ->type('@email', $testUserEmail)
                ->click('@neu-user-profile-save-btn')
                ->pause(2000)
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertDontSeeIn('@neu-header-actions', 'Logout');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeUpdateUserProfilePageAfterLoginAndOnAdmin() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertPathIs('/profile/update')
                ->assertSeeIn('@neu-heading','Update Profile')
                ->assertSee( 'Name')
                ->assertSee( 'Email')
                ->assertSee( 'Password')
                ->assertDontSee( 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckDefaultUserDataOnAdmin() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->assertInputValueIsNot('@name', 'not default user name')
                ->assertInputValue('@email', $user->email)
                ->assertInputValueIsNot('@email', 'not default user email');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckUpdateUserNameOnAdmin() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = 'Client Client';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->assertInputValue('@name', $testUserName)
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserLogoutAfterChangingEmailOnAdmin() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserEmail = 'example@gmail.com';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@email', $user->email)
                ->value('@email','')
                ->type('@email', $testUserEmail)
                ->click('@neu-user-profile-save-btn')
                ->pause(2000)
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertDontSeeIn('@neu-header-actions', 'Logout');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeUpdateUserProfilePageAfterLoginAndOnIt() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertPathIs('/profile/update')
                ->assertSeeIn('@neu-heading','Update Profile')
                ->assertSee( 'Name')
                ->assertSee( 'Email')
                ->assertSee( 'Password')
                ->assertDontSee( 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckDefaultUserDataOnIt() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->assertInputValueIsNot('@name', 'not default user name')
                ->assertInputValue('@email', $user->email)
                ->assertInputValueIsNot('@email', 'not default user email');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckUpdateUserNameOnIt() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = 'Client Client';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->assertInputValue('@name', $testUserName)
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserLogoutAfterChangingEmailOnIt() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserEmail = 'example@gmail.com';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@email', $user->email)
                ->value('@email','')
                ->type('@email', $testUserEmail)
                ->click('@neu-user-profile-save-btn')
                ->pause(2000)
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertDontSeeIn('@neu-header-actions', 'Logout');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeUpdateUserProfilePageAfterLoginAndOnNeubus() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertPathIs('/profile/update')
                ->assertSeeIn('@neu-heading','Update Profile')
                ->assertSee( 'Name')
                ->assertSee( 'Email')
                ->assertSee( 'Password')
                ->assertDontSee( 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckDefaultUserDataOnNeubus() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->assertInputValueIsNot('@name', 'not default user name')
                ->assertInputValue('@email', $user->email)
                ->assertInputValueIsNot('@email', 'not default user email');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCheckUpdateUserNameOnNeubus() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = 'Client Client';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@name', $user->name)
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->assertInputValue('@name', $testUserName)
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserLogoutAfterChangingEmailOnNeubus() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserEmail = 'example@gmail.com';
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->assertInputValue('@email', $user->email)
                ->value('@email','')
                ->type('@email', $testUserEmail)
                ->click('@neu-user-profile-save-btn')
                ->pause(2000)
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertDontSeeIn('@neu-header-actions', 'Logout');
        });
    }
}
