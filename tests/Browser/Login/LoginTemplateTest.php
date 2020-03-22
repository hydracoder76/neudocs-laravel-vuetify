<?php

namespace Tests\Browser\Login;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class LoginTemplateTest
 * @package Tests\Browser\Login
 */
class LoginTemplateTest extends DuskTestCase
{

	/**
	 * @throws \Throwable
	 */
    public function testCanSeeLoginPage() {
    	$this->browse(function(Browser $browser) {
    		$browser->visitRoute('login.form.view')
			    ->assertSee('Please login below');
    	});
    }



}
