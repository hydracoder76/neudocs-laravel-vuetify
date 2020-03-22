<?php
/**
 * User: mlawson
 * Date: 2018-12-12
 * Time: 13:15
 */

namespace Tests\Browser\Forms;

use Tests\DuskTestCase;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use Laravel\Dusk\Browser;

/**
 * Class CreateIndexComponentTest
 * @package Tests\Browser\Forms
 */
class CreateIndexComponentTest extends DuskTestCase
{

	/**
	 * @throws \Throwable
	 */
	public function testCanSeeCreateIndexModal() {
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);

		$this->browse(function (Browser $browser) use ($user, $project) {
			$browser->loginAs($user)
				->visitRoute('admin.indexing')
				->waitFor('@vue-single-select')
				->click('@vue-single-select')
				->click('ul.dropdown li')
				->waitFor('@add-index-button')
				->pause(1000)
				->click('@add-index-button')
				->waitFor('@neu-create-index-form')
				->assertPresent('h5.modal-title');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testCanSeeIndexLabelsOnModal() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);

		$this->browse(function (Browser $browser) use ($user, $project) {
			$browser->loginAs($user)
				->visitRoute('admin.indexing')
				->waitFor('@vue-single-select')
				->click('@vue-single-select')
				->click('ul.dropdown li')
				->waitFor('@add-index-button')
				->pause(1000)
				->click('@add-index-button')
				->waitFor('@neu-create-index-form')
				->assertSee( 'Index Type')
				->assertSee( 'Index Name')
				->assertSee('Index Description');
		});
	}
}