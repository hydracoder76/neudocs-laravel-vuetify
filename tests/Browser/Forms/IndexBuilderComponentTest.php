<?php
/**
 * User: mlawson
 * Date: 2018-12-12
 * Time: 11:14
 */

namespace Tests\Browser\Forms;

use Tests\DuskTestCase;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use Laravel\Dusk\Browser;

/**
 * Class IndexBuilderComponentTest
 * @package Tests\Browser\Forms
 */
class IndexBuilderComponentTest extends DuskTestCase
{

	/**
	 * @throws \Throwable
	 */
	public function testTemplateHasDefaultComponents() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);
		// making sure the indexing template has the builder and dropdown components
		$this->browse(function(Browser $browser) use ($user, $project) {
			$browser->loginAs($user)
				->visitRoute('admin.indexing')
				->assertSee('Select an existing index to modify')
				->assertPresent('@neu-field-builder')
				->assertPresent('@neu-dropdown');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testCanFieldBuilderHasCorrectData() {
        $this->markTestIncomplete('Needs further refactoring before viable');
		$project = factory(Project::class)->create();
		$user = factory(User::class)->create([
			'role' => User::ROLE_IT,
			'company_id' => $project->company_id
		]);

		$this->browse(function (Browser $browser) use ($user, $project) {
			$browser->loginAs($user)
				->visitRoute('admin.indexing')
				->assertVue('tableName', 'Indexing', '@neu-field-builder')
				->assertVue('showTable', false, '@neu-field-builder')
				->assertVue('waitingIndicator', false, '@neu-field-builder')
				->assertVue('waitingText', 'Fetching Indexes', '@neu-field-builder')
				->assertVue('errorMsg', false, '@neu-field-builder');
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testCanSelectProjectFromDropdown() {
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
				->assertInputValue('#single-select',$project->project_name);
		});
	}

	/**
	 * @throws \Throwable
	 */
	public function testSelectedProjectDisplaysTable() {
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
				->assertInputValue('#single-select', $project->project_name)
				->waitFor('@index-builder-neu-table')
				->assertVueContains('title', 'Indexing', '@index-builder-neu-table');
		});
	}
}