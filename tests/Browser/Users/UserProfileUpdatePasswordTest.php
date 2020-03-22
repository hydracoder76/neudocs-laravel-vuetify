<?php

namespace Tests\Browser\Users;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;

class UserProfileUpdatePasswordTest extends DuskTestCase
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
    public function setUp() : void {
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
    public function data() : void {
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
    public function deleteData() : void {
        Project::truncate();
        User::truncate();
        Company::truncate();
    }

    /**
     * @returns void
     */
    public function tearDown() : void {
        $this->deleteData();
        parent::tearDown();
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnEmptyValidationOnIt() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = "It it";
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful')
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnMinLengthValidationOnIt() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'qwerty')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters')
                ->value('@password', '')
                ->type('@password', 'fwerfr')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnUpperLowerCaseValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'qwert12@y')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules')
                ->value('@password', '')
                ->type('@password', 'QW33E$RT12Y')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnTwoNumericValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'Qwert1@Y')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules')
                ->value('@password', '')
                ->type('@password', 'QWe1E$@rtY')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnNumericBeginningValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', '1Q2wert3@Y')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules')
                ->value('@password', '')
                ->type('@password', '6#AddweeTTsd')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnNumericEndingValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'Qwert2@Y1')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules')
                ->value('@password', '')
                ->type('@password', 'Qw#$dfedfewY5')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnSpecialCharacterValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'Qwert2f1Y')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules')
                ->value('@password', '')
                ->type('@password', 'WQerrt12rr')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password violates the password rules');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserConfirmPasswordOnTheSamePasswordValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'Q1wert2@Y')
                ->type('@confirmPassword', 'qwertyuio')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Passwords do not match');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordSuccessValidation() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'Q1wert2@Y')
                ->type('@confirmPassword', 'Q1wert2@Y')
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
    public function testUserPasswordOnEmptyValidationOnClient() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = "Client client";
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful')
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnEmptyValidationOnAdmin() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = "Admin admin";
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful')
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnEmptyValidationOnNeubus() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'password' => bcrypt('password')
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $testUserName = "Neubus neubus";
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful')
                ->value('@name', '')
                ->type('@name', $testUserName)
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Update User Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnMinLengthValidationOnClient() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'qwerty')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters')
                ->value('@password', '')
                ->type('@password', 'fwerfr')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnMinLengthValidationOnAdmin() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'qwerty')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters')
                ->value('@password', '')
                ->type('@password', 'fwerfr')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserPasswordOnMinLengthValidationOnNeubus() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('user.updateprofile')
                ->type('@password', 'qwerty')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters')
                ->value('@password', '')
                ->type('@password', 'fwerfr')
                ->click('@neu-user-profile-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'Password length must be at least 8 characters');
        });
    }
}
