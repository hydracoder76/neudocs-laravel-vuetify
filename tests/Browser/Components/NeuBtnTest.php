<?php
/**
 * User: mlawson
 * Date: 2019-01-14
 * Time: 11:56
 */

namespace Tests\Browser\Components;


use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;

/**
 * Class NeuBtnTest
 * @package Tests\Browser\Components
 */
class NeuBtnTest extends DuskTestCase
{

	/**
	 * @throws \Throwable
	 */
	public function testNeuBtnHasCorrectText() {
	    $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);
		$this->browse(function (Browser $browser) use ($user){
			$browser->loginAs($user)
				->visitRoute('admin.users')
				->pause(500)
				->assertPresent('@neu-add-btn')
				->assertPresent('@neu-btn')
				->assertVue('btnType', 'action', '@neu-todo-upload-btn')
				->assertVue('btnSize', 'md', '@neu-todo-upload-btn')
				->assertVue('btnText', 'button text', '@neu-todo-upload-btn');
		});
	}
}