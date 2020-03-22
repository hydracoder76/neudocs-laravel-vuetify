<?php

namespace Tests\Browser\Neubus;

use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Project;
use Tests\DuskTestCase;

/**
 * Class NeubusComponentTest
 * @package Tests\Browser\Login
 */
class NeubusComponentTest extends DuskTestCase
{


	/**
	 * @throws \Throwable
	 */
	public function testCanSeeLoginForNeubusDashboardWithoutLogin() {
		$this->browse(function (Browser $browser) {
			$browser->visitRoute('neubus.home')
				->pause(1000)
                ->assertVisible('@login-form');
		});
	}

    /**
     * @deprecated
     * @throws \Throwable
     */
    public function testCanSeeNeubusDashboardAfterLogin() {
	    $this->markTestSkipped('Deprecated test');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('neubus.home')
                ->assertSee( 'Welcome to the Neubus Dashboard');
        });
    }

}
