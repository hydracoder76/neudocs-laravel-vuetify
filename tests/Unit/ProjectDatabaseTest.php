<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 12:41 PM
 */

namespace Tests\Unit;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use Tests\TestCase;

/**
 * Class ProjectDatabaseTest
 * @package Tests
 */
class ProjectDatabaseTest extends TestCase
{


	public function testCompanyRelation() {
		$company = factory(Company::class)->create();
		$projects = factory(Project::class,2)->create([
			'company_id' => $company->id
		]);

		$this->assertDatabaseHas('projects', ['company_id' => $projects->first()->company->id]);
		self::assertEquals(2, Project::whereCompanyId($company->id)->count());
		self::assertEquals($company->id, Project::find($projects->first()->id)->company->id);
	}

	public function testOwnerRelation() {
		$owners = factory(User::class)->create();
		$projects = factory(Project::class,8)->create([
			'project_owner_id' => $owners->id
		]);

		$this->assertDatabaseHas('projects', ['project_owner_id' => $projects->first()->owner->id]);
		self::assertEquals(8, Project::whereProjectOwnerId($owners->id)->count());
		self::assertEquals($owners->id, Project::find($projects->first()->id)->owner->id);
	}

	public function testBoxesRelation() {

			$project = factory(Project::class)->create();
			factory(Box::class, 5)->create([
				'project_id' => $project->id
			]);
			$this->assertDatabaseHas('projects', ['id' => $project->boxes->first()->project_id]);
			self::assertEquals(5, Box::whereProjectId($project->id)->count());

	}

	public function testSchemasRelation() {
		$this->markTestIncomplete('Will not really be testable until schema is finalized, to an extent');
	}

}