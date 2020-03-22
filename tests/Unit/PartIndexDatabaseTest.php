<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 12:15 PM
 */

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Indexing\ProjectSchema;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Relational\ProjectPartIndex;
use Tests\TestCase;

/**
 * Class PartIndexDatabaseTest
 * @package Tests\Unit
 */
class PartIndexDatabaseTest extends TestCase
{



	public function testPartRelation() {
		self::markTestIncomplete('Test will be available when partition system is in place');
		$part = factory(Part::class)->create();
		$partIndexes = factory(PartIndex::class,2)->create([
			'part_id' => $part->id
		]);

		$this->assertDatabaseHas('parts', ['id' => $partIndexes->first()->part->id]);
		self::assertEquals(2, PartIndex::wherePartId($part->id)->count());
		self::assertEquals($part->id, PartIndex::wherePartId($partIndexes->first()->part_id)->first()->part->id);
	}


}