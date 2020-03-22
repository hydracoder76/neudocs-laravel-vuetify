<?php

namespace Tests\Browser\Pages;

use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class NavBarTest
 * @package Tests\Browser\Pages
 */
class NavBarTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testCanSeeNavBar() {
        //add login to step once implemented
	    $user = factory(User::class)->create([
	    	'role' => User::ROLE_IT
	    ]);
        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)
	            ->visitRoute('admin.home')
                ->assertSee('Dashboard');
        });
    }

	/**
	 * @throws \Throwable
	 */
    public function testNavRequest(){
    	$this->markTestSkipped('This test needs to be fixed for the sidebar');
        $this->browse(function(Browser $browser) {
            $browser->visitRoute('requests')
                ->clickLink('Requests')
                ->clickLink('Project1')
                ->pause(500)
                ->assertPathIs('/requests');
        });
    }

}
