<?php
/**
 * User: mlawson
 * Date: 2019-01-14
 * Time: 13:47
 */

namespace Tests\Browser\Requests\Todo;


use Tests\DuskTestCase;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use Laravel\Dusk\Browser;
/**
 * Class TodoLandingTest
 * @package Tests\Browser\Requests\Todo
 */
class TodoLandingTest extends DuskTestCase
{

	/**
	 * @throws \Throwable
	 */
	public function testUploadBtnPresent() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);
		$this->browse(function (Browser $browser) use ($user){
			$browser->loginAs($user)
				->visitRoute('todo.home')
				->pause(500)
				->assertPresent('@neu-todo-upload-btn');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testUploadBtnText() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);
		$this->browse(function (Browser $browser) use ($user){
			$browser->loginAs($user)
				->visitRoute('todo.home')
				->pause(500)
				->assertVue('btnText', 'Upload', '@neu-todo-upload-btn');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testUploadBtnLink() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);
		$this->browse(function (Browser $browser) use ($user) {
			$browser->loginAs($user)
				->visitRoute('todo.home')
				->pause(500)
				->assertVue('btnHref', route('todo.upload'), '@neu-btn-comp')
				->click('@neu-btn')
				->assertRouteIs(route('todo.upload'));
		});
	}
}