<?php
/**
 * User: mlawson
 * Date: 11/14/18
 * Time: 10:53 AM
 */

namespace Tests\Browser\Login;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;

/**
 * Class MfaComponentTest
 * @package Tests\Browser\Login
 */
class MfaComponentTest extends DuskTestCase
{

    /**
     * NOTE: Many assertions are in
     */
	use WithoutMiddleware;

    /**
     *
     */
	public function tearDown() : void {
	    User::truncate();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /**
	 * @throws \Throwable
	 */
	public function testHasMfaField() : void {
		$srmUser = factory(User::class)->create([
            'otp_secret' => str_random(),
            'email' => 'has_mfa_field_'.DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_NEUBUS
		]);

		// event will cause has_mfa to be false on load, leave this alone
		$srmUser->has_mfa = true;
		$srmUser->save();

		$this->browse(function(Browser $browser) use ($srmUser) {

			$browser->visitRoute('login.form.view')
				->type('@email', $srmUser->email)
				->type('@password', 'secret')
				->click('@neu-login-btn')
                ->assertMissing('#neu-mfa-setup-cont')
				->assertPathIs('/login')
				->waitFor('@neu-submit-mfa', 10)
				->assertSee('MFA Token')
				->assertVue('showMfa', true, '@login-form')
				->assertVue('hasMfa', true, '@login-form');
		});

	}

	/**
	 * @throws \Throwable
	 */
	public function testHasSixMfaFields() : void {
		$srmUser = factory(User::class)->create([
			'has_mfa' => true,
            'otp_secret' => str_random(),
            'role' => User::ROLE_NEUBUS,
            'email' => 'has_six_mfa_fields_'.DuskTestCase::USER_EMAIL
		]);

        // event will cause has_mfa to be false on load, leave this alone
        $srmUser->has_mfa = true;
        $srmUser->save();

		$this->browse(function (Browser $browser) use ($srmUser) {

			$browser->visitRoute('login.form.view')
				->type('@email', $srmUser->email)
				->type('@password', 'secret')
				->click('@neu-login-btn')
                ->assertMissing('#neu-mfa-setup-cont')
				->assertPathIs('/login')
				->waitFor('@neu-submit-mfa', 10)
				->assertPresent('@neu-mfa-token');


		});

	}

    /**
     * @throws \Throwable
     */
	public function testSetupUserNotRequired() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'setup_user_not_required_'.DuskTestCase::USER_EMAIL,
            'has_mfa' => false,
            'role' => User::ROLE_NEUBUS
        ]);


        $this->browse(function(Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertMissing('#neu-mfa-setup-cont')
                ->assertPathIsNot('/neubus') // let's go ahead and also check that mfa doesn't somehow bring us
                    // to what used to be the old dashboard
                ->assertPathIs('/requests/todo')
                ->assertMissing('@neu-mfa-form')
                ->assertMissing('@neu-submit-mfa')
                ->pause(500)
                ->assertPresent('@vue-single-select')
                ->assertPresent('@todo-form');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testSetupRequiredIt() : void {
	    $srmUser = factory(User::class)->create([
	        'email' => 'setup_required_it_'.DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_IT
        ]);

	    $this->browse(function(Browser $browser) use ($srmUser) {
	        $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please setup your multi factor authentication device')
                ->assertPathIsNot('/admin/users')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testSetupRequiredClient() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'setup_required_client_'.DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function(Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please setup your multi factor authentication device')
                ->assertPathIsNot('/requests')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testSetupRequiredClientAdmin() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'setup_required_client_admin_'.DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_ADMIN
        ]);

        $this->browse(function(Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please setup your multi factor authentication device')
                ->assertPathIsNot('/requests')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu');

        });
    }
}