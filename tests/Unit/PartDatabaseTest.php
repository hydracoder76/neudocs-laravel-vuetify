<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 11:54 AM
 */

namespace Tests\Unit;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Org\Project;
use Tests\TestCase;

/**
 * Class PartDatabaseTest
 * @package Tests\Unit
 */
class PartDatabaseTest extends TestCase
{

	public function testBoxRelation() {

		$box = factory(Box::class)->create();
		$parts = factory(Part::class, 3)->create([
			'box_id' => $box->id
		]);

		$this->assertDatabaseHas('parts', ['box_id' => $parts->first()->box->id]);
		self::assertEquals(3, Part::whereBoxId($box->id)->count());
		self::assertEquals($box->id, Part::find($parts->first()->id)->box->id);
	}

	public function testProjectRelation() {

		$project = factory(Project::class)->create();
		$parts = factory(Part::class, 3)->create([
			'project_id' => $project->id
		]);

		$this->assertDatabaseHas('parts', ['project_id' => $parts->first()->project->id]);
		self::assertEquals(3, Part::whereProjectId($project->id)->count());
		self::assertEquals($project->id, Part::find($parts->first()->id)->project->id);
	}

	public function testCreatedByRelation() {
		$srmUser = factory(User::class)->create();
		factory(Part::class, 3)->create([
			'created_by' => $srmUser->id
		]);

		$this->assertDatabaseHas('parts', ['created_by' => $srmUser->id]);
		self::assertEquals(3, Part::whereCreatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Part::whereCreatedBy($srmUser->id)->first()->created_by)->first()->id);
	}

	public function testUpdatedByRelation() {

		$srmUser = factory(User::class)->create();
		factory(Part::class, 4)->create([
			'last_updated_by' => $srmUser->id
		]);
		$this->assertDatabaseHas('parts', ['last_updated_by' => $srmUser->id]);
		self::assertEquals(4, Part::whereLastUpdatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Part::whereLastUpdatedBy($srmUser->id)->first()->last_updated_by)->first()->id);
	}

	public function testIndexesRelation() {
		$part = factory(Part::class)->create();
		$indexes = factory(PartIndex::class)->create([
			'part_id' => $part->id
		]);

		$this->assertDatabaseHas('parts', ['id' => $indexes->part_id]);
		self::assertEquals(1, Part::whereId($indexes->part_id)->count());
		self::assertEquals($part->id, Part::find($part->id)->indexes->first()->part_id);
	}

}