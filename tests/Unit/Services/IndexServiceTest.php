<?php
/**
 * User: mlawson
 * Date: 2018-12-16
 * Time: 13:41
 */

namespace Tests\Unit\Services;

use Faker\Factory as Faker;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Services\IndexService;
use Tests\TestCase;

/**
 * Class IndexServiceTest
 * @package Tests\Unit\Services
 */
class IndexServiceTest extends TestCase
{

	/**
	 * @var IndexService
	 */
	protected $indexService;


	protected $user;


	public function setUp() {
		parent::setUp();
		$this->user = factory(User::class)->create([
			'role' => 'admin'
		]);
		$this->indexService = resolve(IndexService::class);

	}

	public function testGetIndexTypes() {
		$nameList = $this->indexService->getIndexTypeNameList();
		self::assertIsArray($nameList);
		foreach ($nameList as $name) {
			self::assertTrue(in_array($name, config('srm.index_type_names')));
		}
		self::assertTrue(is_array($nameList));
	}

	/**
	 * @param array $data
	 * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
	 * @throws \Throwable
	 * @covers \NeubusSrm\Services\IndexService::saveNewIndexesForProject
	 * @dataProvider newIndexDataProvider
	 */
	public function testAddNewIndexValid(array $data) {
		$this->actingAs($this->user);
		$newIndex = $this->indexService->saveNewIndexesForProject($data);

		self::assertNotNull($newIndex);
		self::assertIsInt($newIndex);

	}

	/**
	 * @param array $data
	 * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
	 * @throws \Throwable
	 * @covers \NeubusSrm\Services\IndexService::saveNewIndexesForProject
	 * @expectedException \NeubusSrm\Lib\Exceptions\NeuDataStoreException
	 * @dataProvider newIndexDataProvider
	 */
	public function testAddNewIndexInvalid(array $data) {
		$this->actingAs($this->user);
		$data['index_name'] = null; // this invalidates it
		$newIndex = $this->indexService->saveNewIndexesForProject($data);
		self::assertNull($newIndex);

	}

	/**
	 * @param array $data
	 * @dataProvider newIndexDataProvider
	 */
	public function testAddNewIndexWithDefaults(array $data) {
		try {
			// unset those items which don't have defaults or are nullable
			unset($data['is_required']);
			unset($data['is_required_double']);

			$newIndex = $this->indexService->saveNewIndexesForProject($data);

			self::assertNotNull($newIndex);
			self::assertIsInt($newIndex);
		}
		catch (NeuSrmException | \Throwable $exception) {
			self::fail('Exception occurred in saving: ' . $exception->getMessage());
		}
	}

	/**
	 * Returns a valid data set, which can then be overridden by tests
	 * that need to test invalid data
	 * @return array
	 */
	public function newIndexDataProvider() {

		$faker = Faker::create();

		return [
			[
				[

					'index_type_id' => 0,
					'index_name' => 'A label',
					'index_internal_name' => 'a_label',
					'index_description' => 'a description',
					'project_id' => $faker->uuid,
					'is_required' => false,
					'is_required_double' => false

				]
			]

		];

	}

	public function tearDown() {
		$this->user->forceDelete();
		unset($this->user);
		unset($this->indexService);
		parent::tearDown();
	}
}