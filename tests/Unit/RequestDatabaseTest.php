<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 12:59 PM
 */

namespace Tests\Unit;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Request;
use Tests\TestCase;

/**
 * Class RequestDatabaseTest
 * @package Tests\Unit
 */
class RequestDatabaseTest extends TestCase
{


	public function testCompanyRelation() {
		$company = factory(Company::class)->create();
		$requests = factory(Request::class,12)->create([
			'company_id' => $company->id
		]);

		$this->assertDatabaseHas('requests', ['company_id' => $requests->first()->company->id]);
		self::assertEquals(12, Request::whereCompanyId($company->id)->count());
		self::assertEquals($company->id, Request::find($requests->first()->id)->company->id);
	}

	public function testCreatedByRelation() {

		$srmUser = factory(User::class)->create();
		factory(Request::class)->create([
			'created_by' => $srmUser->id
		]);
		$this->assertDatabaseHas('requests', ['created_by' => $srmUser->id]);
		self::assertEquals(1, Request::whereCreatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Request::whereCreatedBy($srmUser->id)->first()->created_by)->first()->id);
	}

	public function testUpdatedByRelation() {

		$srmUser = factory(User::class)->create();
		factory(Request::class)->create([
			'updated_by' => $srmUser->id
		]);
		$this->assertDatabaseHas('requests', ['updated_by' => $srmUser->id]);
		self::assertEquals(1, Request::whereUpdatedBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Request::whereUpdatedBy($srmUser->id)->first()->updated_by)->first()->id);
	}

	public function testFulfilledByRelation() {

		$srmUser = factory(User::class)->create();
		factory(Request::class)->create([
			'fulfilled_by' => $srmUser->id
		]);
		$this->assertDatabaseHas('requests', ['fulfilled_by' => $srmUser->id]);
		self::assertEquals(1, Request::whereFulfilledBy($srmUser->id)->count());
		self::assertEquals($srmUser->id, User::whereId(Request::whereFulfilledBy($srmUser->id)->first()->fulfilled_by)->first()->id);
	}
}