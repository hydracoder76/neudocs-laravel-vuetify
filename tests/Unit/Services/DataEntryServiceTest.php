<?php

namespace Tests\Unit\Services;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Services\DataEntryService;
use NeubusSrm\Services\ProjectService;
use Faker\Factory as Faker;
use Tests\TestCase;
use DB, Storage;


/**
 * Class DataEntryServiceTest
 * @package Tests\Unit
 */
class DataEntryServiceTest extends TestCase
{
    /**
     * @var DataEntryService
     */
    protected $dataEntryService;


    /**
     * @return void
     */
    public function setUp() : void{
        parent::setUp();
        $this->dataEntryService = resolve(DataEntryService::class);
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass() : void{
        $command = 'php ' . base_path() . '/artisan neusrm:truncate --env=testing';
        exec($command);
        parent::tearDownAfterClass();
    }

    /**
     * @dataProvider projectBoxesProvider
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @param array $expected
     * @param int $total
     */
    public function testProjectBoxes(string $projectId, string $keyword, string $sortBy, string $order, array $expected, int $total) : void{
        $boxes = $this->dataEntryService->projectBoxes($projectId, $keyword, $sortBy, $order);
        $this->assertEquals($total, $boxes->total());
        foreach($boxes->getCollection() as $key => $box){
            $this->assertEquals($box->box_name, $expected[$key]['box_name']);
            $this->assertEquals($box->createdBy->name, $expected[$key]['created_by']);
        }
    }

    /**
     * @return array
     */
    public function projectBoxesProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $user1 = factory(User::class)->create(['name' => 'aaa']);
        $user2 = factory(User::class)->create(['name' => 'bbb']);
        $user3 = factory(User::class)->create(['name' => 'ccc']);
        $box1 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'xxxddd', 'created_by' => $user1->id]);
        $box2 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'yyyeee', 'created_by' => $user2->id]);
        $box3 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'fffxxx', 'created_by' => $user3->id]);
        return [[$project1->id, 'xxx', 'created_by_name', 'asc', [['box_name'=>'xxxddd', 'created_by'=>'aaa'], ['box_name'=>'fffxxx', 'created_by'=>'ccc']], 2]];
    }
}
