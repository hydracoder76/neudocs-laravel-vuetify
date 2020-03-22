<?php
/**
 * User: mlawson
 * Date: 11/15/18
 * Time: 1:25 PM
 */

namespace Tests\Unit;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Auth\VerificationToken;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Company;
use Tests\TestCase;

/**
 * Class UserDatabaseTest
 * @package Tests\Unit
 */
class UserDatabaseTest extends TestCase
{


	public function testCompanyRelation() {

		$company = factory(Company::class)->create();
		$srmUser = factory(User::class, 2)->create([
			'company_id' => $company->id
		]);

		$this->assertDatabaseHas('users', ['company_id' => $srmUser->first()->company->id]);
		self::assertEquals(2, User::whereCompanyId($company->id)->count());
		self::assertEquals($company->id, User::find($srmUser->first()->id)->company->id);

	}

	public function testPartsRelation() {
			$part = factory(Part::class)->create();
			$user = User::whereId($part->created_by)->first();
			$this->assertDatabaseHas('parts', ['created_by' => $user->parts->first()->created_by]);
			$this->assertDatabaseHas('parts', ['id' => $user->parts->find($part->id)->id]);
	}

	public function testTokenRelation() {
		$token = factory(VerificationToken::class)->create();
		factory(User::class)->create([
			'verification_token_id' => $token->id
		]);
		$this->assertDatabaseHas('verification_tokens', ['id' => $token->id]);
		self::assertEquals(1, User::whereVerificationTokenId($token->id)->count());
		self::assertEquals($token->id, User::whereVerificationTokenId($token->id)->first()->token->id);
	}

	public function testBoxesRelation() {
		$box = factory(Box::class)->create();
		$user = User::whereId($box->created_by)->first();
		$this->assertDatabaseHas('boxes', ['created_by' => $user->boxes->first()->created_by]);
		$this->assertDatabaseHas('boxes', ['id' => $user->boxes->find($box->id)->id]);
	}
}