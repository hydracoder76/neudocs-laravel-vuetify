<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 10:35 AM
 */

namespace Tests\Unit;


use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use Tests\TestCase;

/**
 * Class BoxDatabaseTest
 * @package Tests\Unit
 */
class BoxDatabaseTest extends TestCase
{


	public function testCompanyRelation() {
		$company = factory(Company::class)->create();
		$boxes = factory(Box::class,2)->create([
			'company_id' => $company->id
		]);

		$this->assertDatabaseHas('boxes', ['company_id' => $boxes->first()->company->id]);
		self::assertEquals(2, Box::whereCompanyId($company->id)->count());
		self::assertEquals($company->id, Box::find($boxes->first()->id)->company->id);
	}

	public function testProjectRelation() {
		$project = factory(Project::class)->create();
		$boxes = factory(Box::class, 5)->create([
			'project_id' => $project->id
		]);

		$this->assertDatabaseHas('boxes', ['project_id' => $boxes->first()->project->id]);
		self::assertEquals(5, Box::whereProjectId($project->id)->count());
		self::assertEquals($project->id, Box::find($boxes->first()->id)->project->id);
	}

	public function testCreatedByRelation() {
		$srmUser = factory(User::class)->create();
		$box = factory(Box::class)->create([
			'created_by' => $srmUser->id
		]);

		$this->assertDatabaseHas('boxes', ['created_by' => $srmUser->id]);
		self::assertEquals(1, Box::whereCreatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Box::whereCreatedBy($srmUser->id)->first()->created_by)->first()->id);
	}

	public function testUpdatedByRelation() {
		$srmUser = factory(User::class)->create();
		$box = factory(Box::class)->create([
			'updated_by' => $srmUser->id
		]);

		$this->assertDatabaseHas('boxes', ['updated_by' => $srmUser->id]);
		self::assertEquals(1, Box::whereUpdatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Box::whereUpdatedBy($srmUser->id)->first()->updated_by)->first()->id);
	}
}