<?php

namespace Tests\Browser\Forms;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class FormComponentTest
 * @package Tests\Browser\Forms
 */
class FormComponentTest extends DuskTestCase
{

	/**
	 * This is really the only test we can do dusk wise as we can't set props, only see if the component directly
	 * contains given data and such. We'll test the initial state
	 * @throws \Throwable
	 */
	public function testHasFormComponentWithDefaultProps() {
		$this->browse(function (Browser $browser) {
			$browser->visitRoute('login.form.view')
				->pause(500)
				->assertPresent('@neu-form')
				->assertVue('isModal', false, '@neu-form')
				->assertVue('formHeader', '', '@neu-form')
				->assertVue('showModal', false, '@neu-form')
				->assertVue('regularFormSubmit', true, '@neu-form');
		});
	}




}
