<?php

namespace Tests\Browser\Login;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class LoginComponentTest
 * @package Tests\Browser\Login
 */
class LoginComponentTest extends DuskTestCase
{


	/**
	 * @throws \Throwable
	 */
	public function testHasEmailField() {
		$this->browse(function (Browser $browser) {
			$browser->visitRoute('login.form.view')
				->pause(1000)
				->type('@email', 'user@gmail.com')
				->assertVue('email', 'user@gmail.com', '@login-form')
				->click('@neu-clear-btn')
				->type('@email','other@gmail.com')
				->assertVue('email','other@gmail.com', '@login-form');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testHasPasswordField() {
		$this->browse(function (Browser $browser) {
			$browser->visitRoute('login.form.view')
				->pause(1000)
				->type('@password', 'temp123')
				->assertVue('password', 'temp123', '@login-form')
				->click('@neu-clear-btn')
				->type('@password','other123')
				->assertVue('password', 'other123', '@login-form');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testResetBtnResets() {
		$this->browse(function(Browser $browser) {
			$browser->visitRoute('login.form.view')
				->pause(500)
				->type('@password', 'temp123')
				->type('@email', 'email@email.com')
				->assertVue('password', 'temp123', '@login-form')
				->assertVue('email', 'email@email.com', '@login-form')
				->click('@neu-clear-btn')
				->assertVue('password', '', '@login-form')
				->assertVue('email', '', '@login-form')
			;
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testSubmitBtnSetsFlag() {
		$this->browse(function(Browser $browser) {
			$browser->visitRoute('login.form.view')
				->pause(500)
				->assertVue('isSubmitting', false, '@login-form')
				->type('@password', 'temp123')
				->type('@email', 'email@email.com')
				->click('@neu-login-btn')
				->assertVue('isSubmitting', true, '@login-form');
		});
	}


}
