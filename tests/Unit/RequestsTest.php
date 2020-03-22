<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestsCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Index;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Indexing\PartIndex;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Services\RequestService;
use Faker\Factory as Faker;
use NeubusSrm\Services\UserService;
use Tests\TestCase;
use DB, Storage;
use Carbon\Carbon;

const USER_PASSWORD = 'password';

/**
 * Class RequestsTest
 * @package Tests\Unit
 */
class RequestsTest extends TestCase
{
    /**
     * @var RequestService
     */
    protected $requestService;

    protected $lastTest = false;

    /**
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     * @throws \NeubusSrm\Lib\Exceptions\NeuUserInvalidCredentialsException
     * @throws \Throwable
     */
    public function setUp() : void{
        parent::setUp();
        $this->requestService = resolve(RequestService::class);
        $user = factory(User::class)->create(['password' => bcrypt(USER_PASSWORD)]);
        $userService = resolve(UserService::class);
        $userService->loginUser($user->email, USER_PASSWORD);

    }

    public static function tearDownAfterClass(){
        exec('echo yes | php artisan neusrm:truncate --env=testing');
        parent::tearDownAfterClass();
    }

    /**
     * @dataProvider requestByIndexProvider
     * @param PartsCollection $parts
     * @param string $projectId
     * @param array $expected
     * @param array $expectedNot
     */
    public function testRequestByIndex(PartsCollection $parts, string $projectId, array $expected, array $expectedNot, int $count){
        $requestCollections = $this->requestService->getRequestsByIndexes($parts, $projectId, '', '');
        $this->assertEquals($count, $requestCollections['total']);
        foreach($requestCollections['requests'] as $requestCollection){
            $this->assertArrayHasKey($requestCollection->id, $expected);
            $this->assertArrayNotHasKey($requestCollection->id, $expectedNot);
            $this->assertEquals($expected[$requestCollection->id]['request_name'], $requestCollection->request_name);
        }
    }

    /**
     * @return array
     */
    public function requestByIndexProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $request1 = factory(Request::class)->create(['project_id' => $project1->id, 'request_name'=>'request1']);
        $request2 = factory(Request::class)->create(['project_id' => $project1->id, 'request_name'=>'request2']);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id]);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id]);
        $request1->parts()->attach($part1->id);
        $request2->parts()->attach($part1->id);
        $request2->parts()->attach($part2->id);
        $parts1 = new PartsCollection([$part1]);
        $parts2 = new PartsCollection([$part2]);
        return [[$parts2, $project1->id, [$request2->id => ['request_name'=>'request2']], [$request1->id => ''], 1],
            [$parts1, $project1->id, [$request1->id => ['request_name'=>'request1'], $request2->id => ['request_name'=>'request2']], [], 2]];
    }

    /**
     * @dataProvider requestByIndexSortProvider
     * @param PartsCollection $parts
     * @param string $projectId
     * @param string $sortBy
     * @param string $order
     * @param array $expected
     * @param int $count
     */
    public function testRequestByIndexSort(PartsCollection $parts, string $projectId, string $sortBy, string $order, array $expected, int $count) {
        $requestCollections = $this->requestService->getRequestsByIndexes($parts, $projectId, $sortBy, $order);
        $this->assertEquals($count, $requestCollections['total']);
        foreach($requestCollections['requests'] as $key => $requestCollection){
            $this->assertEquals($expected[$key]['request_name'], $requestCollection->request_name);
        }
    }

    /**
     * @return array
     */
    public function requestByIndexSortProvider() : array {
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $request1 = factory(Request::class)->create(['project_id' => $project1->id, 'request_name'=>'request1']);
        $request2 = factory(Request::class)->create(['project_id' => $project1->id, 'request_name'=>'request2']);
        $request3 = factory(Request::class)->create(['project_id' => $project1->id, 'request_name'=>'request3']);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id]);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id]);
        $request1->parts()->attach($part1->id);
        $request3->parts()->attach($part1->id);
        $request2->parts()->attach($part2->id);
        $parts1 = new PartsCollection([$part1]);
        return [[$parts1, $project1->id, 'request_number', 'asc',  [['request_name' => 'request1'], ['request_name' => 'request3']], 2]];
    }

    /**
     * @dataProvider requestByProjectProvider
     * @param string $projectId
     * @param array $expected
     * @param array $expectedNot
     */
    public function testRequestByProject(string $projectId, array $expected, array $expectedNot , int $count) : void{
        $requestCollections = $this->requestService->getRequestsByProject($projectId);
        $this->assertEquals($count, $requestCollections->count());
        foreach($requestCollections as $requestCollection){
            $this->assertArrayHasKey($requestCollection->id, $expected);
            $this->assertArrayNotHasKey($requestCollection->id, $expectedNot);
        }
    }

    /**
     * @return array
     */
    public function requestByProjectProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $project2 = factory(Project::class)->create();
        $request1 = factory(Request::class)->create(['project_id' => $project1->id]);
        $request2 = factory(Request::class)->create(['project_id' => $project1->id]);
        $request3 = factory(Request::class)->create(['project_id' => $project2->id]);
        $request4 = factory(Request::class)->create(['project_id' => $project2->id]);
        return [[$project1->id, [$request1->id => '', $request2->id => ''], [$request3->id => '', $request4->id => ''], 2],
            [$project2->id, [$request3->id => '', $request4->id => ''], [$request1->id => '', $request2->id => ''], 2]];
    }

    /**
     * @dataProvider requestsToListProvider
     * @param array $params
     * @param array $expected
     */
    public function testRequestsToList(array $params, array $expected) : void{
        $requestLists = $this->requestService->requestsToList($params['requests'], $params['indexTypes']);
        $this->assertEquals(sizeof($params['requests']), sizeof($requestLists));
        foreach($requestLists as $requestList){
            $this->assertArrayHasKey($requestList['request_id'], $expected);
            foreach($expected[$requestList['request_id']] as $attribute => $expectedCell){
                $this->assertEquals($expectedCell, $requestList[$attribute]);
            }
        }
    }

    /**
     * @return array
     */
    public function requestsToListProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $request1 = factory(Request::class)->create(['project_id' => $project1->id, 'is_in_process' => false,
            'is_fulfilled' => false]);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id]);
        $request1->parts()->attach($part1->id);
        $indexType = factory(IndexType::class)->create(['project_id'=>$project1->id]);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType->id,
             'part_index_value' => 'xxx', 'box_id' => 1]);
        $requests1 = new RequestsCollection([$request1]);
        $indexTypes1 = new IndexTypesCollection([$indexType]);
        return [[['requests' => $requests1, 'indexTypes' => $indexTypes1], [$request1->id=>[
            'request_number' => $request1->request_name, 'request_indexes' => 'xxx', 'status' => 'New', 'download' => '']]]];
    }

    /**
     * @dataProvider getPartsByIndexesProvider
     * @param array $indexes
     * @param string $projectId
     * @param bool $paginate
     * @param array $expected
     * @param array $expectedNot
     */
    public function testGetPartsByIndexes(array $indexes, string $projectId, bool $paginate, array $expected, array $expectedNot) : void{
        $partResult = $this->requestService->getPartsByIndexes($indexes, $projectId, $paginate, '', '');
        $this->assertEquals(sizeof($expected), $partResult['total']);
        foreach($partResult['parts'] as $partResult){
            $this->assertArrayHasKey($partResult->id, $expected);
            $this->assertArrayNotHasKey($partResult->id, $expectedNot);
            $this->assertEquals($expected[$partResult->id]['part_name'], $partResult->part_name);
        }
    }

    /**
     * @return array
     */
    public function getPartsByIndexesProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $part1 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '1']);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2']);
        $indexType1 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_internal_name' => 'label1']);
        $indexType2 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_internal_name' => 'label2']);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxx', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxx', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType2->id,
            'part_index_value' => 'yyy', 'box_id' => 1]);
        return [[['label1' => 'xxx'], $project1->id, false, [$part1->id => ['part_name' => '1'],
                $part2->id => ['part_name' => '2']], []],
            [['label1' => 'xxx', 'label2' => 'yyy'], $project1->id, false, [$part2->id => ['part_name' => '2']], [$part1->id => '']],
            [['label2' => 'yyy'], $project1->id, false, [$part2->id => ['part_name' => '2']], [$part1->id => '']],
            [[], $project1->id, true, [$part1->id => ['part_name' => '1'], $part2->id => ['part_name' => '2']], []] ];
    }

    /**
     * @dataProvider getPartsByIndexesSortProvider
     * @param array $indexes
     * @param string $projectId
     * @param string $sortBy
     * @param string $order
     * @param array $expected
     */
    public function testGetPartsByIndexesSort(array $indexes, string $projectId, string $sortBy, string $order, array $expected) : void {
        $partResult = $this->requestService->getPartsByIndexes($indexes, $projectId, true, $sortBy, $order);
        $this->assertEquals(sizeof($expected), $partResult['total']);
        foreach($partResult['parts'] as $key => $partResult){
            $this->assertEquals($expected[$key]['part_name'], $partResult->part_name);
        }
    }

    /**
     * @return array
     */
    public function getPartsByIndexesSortProvider() : array {
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $part1 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '1']);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2']);
        $part3 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '3']);
        $indexType1 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_internal_name' => 'labelSort1']);
        $indexType2 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_internal_name' => 'labelSort2']);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxx', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'yyy', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part3->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxx', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType2->id,
            'part_index_value' => 'aaa', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType2->id,
            'part_index_value' => 'bbb', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part3->id, 'index_type_id' => $indexType2->id,
            'part_index_value' => 'ccc', 'box_id' => 1]);
        return [[['labelSort1' => 'xxx'], $project1->id, 'labelSort2', 'asc', [['part_name' => '1'], ['part_name' => '3']]]];

    }

    /**
     * @dataProvider requestPartsToListProvider
     * @param array $params
     * @param array $expected
     */
    public function testRequestPartsToList(array $params, array $expected) : void{
        $partLists = $this->requestService->requestPartsToList($params['parts'], $params['indexTypes']);
        $this->assertEquals($params['parts']->count(), sizeof($partLists));
        foreach($partLists as $partList){
            $this->assertArrayHasKey($partList['part_id'], $expected);
            foreach($expected[$partList['part_id']] as $attribute => $expectedCell){
                $this->assertEquals($expectedCell, $partList[$attribute]);
            }
        }
    }

    /**
     * @return array
     */
    public function requestPartsToListProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $box1 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'box1']);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '1', 'box_id' => $box1->id]);
        $indexType1 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_label' => 'label1']);
        $indexType2 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_label' => 'label2']);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxx', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType2->id,
            'part_index_value' => 'yyy', 'box_id' => 1]);
        $parts = new PartsCollection([$part1]);
        $indexTypes = new IndexTypesCollection([$indexType1, $indexType2]);
        return [[['parts' => $parts, 'indexTypes' => $indexTypes], [$part1->id=>[
            'part_name' => '1', 'box_name' => 'box1', 'label1' => 'xxx', 'label2' => 'yyy']]]];
    }

    /**
     * @dataProvider newRequestProvider
     * @param string $projectId
     * @param array $parts
     * @param array $expected
     */
    public function testNewRequest(string $projectId, array $parts, array $expected) : void{
        $this->requestService->createNewRequest($parts, $projectId, 'extra comment');
        $request = Request::latest()->first();
        $this->assertEquals($request->parts->count(), sizeof($expected['parts']));
        foreach($request->parts as $part){
            $this->assertArrayHasKey($part->id, $expected['parts']);
        }
        $this->assertEquals($expected['is_in_process'], $request->is_in_process);
        $this->assertEquals($expected['is_fulfilled'], $request->is_fulfilled);
        $name = $expected['project'] . Carbon::now()->format('ymd') . $expected['number'];
        $this->assertEquals($name, $request->request_name);
    }

    /**
     * @return array
     */
    public function newRequestProvider() : array{
        $this->refreshApplication();
        $project1 = factory(Project::class)->create(['project_name' => 'project1']);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id]);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id]);
        return [[$project1->id, [$part1->id, $part2->id], ['parts' => [$part1->id => '', $part2->id => ''], 'is_in_process' => false,
            'is_fulfilled' => false, 'number' => '0000', 'project' => 'project1']]];
    }

    /**
     * @dataProvider getAutoPartsProvider
     * @param string $key
     * @param string $value
     * @param array $expected
     * @param array $expectedNot
     */
    public function testGetAutoParts(string $key, string $value, array $expected, array $expectedNot) : void{
        $partValues = $this->requestService->getAutoPartValues($key, $value);
        $this->assertEquals(sizeof($expected), sizeof($partValues));
        foreach($partValues as $partValue){
            $this->assertArrayHasKey($partValue, $expected);
            $this->assertArrayNotHasKey($partValue, $expectedNot);
        }
    }

    /**
     * @return array
     */
    public function getAutoPartsProvider() : array {
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $part1 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '1']);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2']);
        $part3 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2']);
        $part4 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2']);
        $indexType1 = factory(IndexType::class)->create(['project_id'=>$project1->id, 'index_internal_name' => 'autolabel1']);
        DB::table('part_indexes')->insert(['part_id' => $part1->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxxABcdE', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part2->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'yyyabc', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part3->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'xxxABcdE', 'box_id' => 1]);
        DB::table('part_indexes')->insert(['part_id' => $part4->id, 'index_type_id' => $indexType1->id,
            'part_index_value' => 'zzxxde', 'box_id' => 1]);
        return [['autolabel1', 'abcd', ['xxxABcdE' => ''], ['yyyabe' => '']]];
    }

    /**
     * @dataProvider todoSearchProvider
     * @param string $projectId
     * @param string $keyword
     * @param string $sortBy
     * @param string $order
     * @param int $count
     * @param array $expected
     */
    public function testTodoSearch(string $projectId, string $keyword, string $sortBy, string $order, int $count, array $expected) : void {
        $requests = $this->requestService->todoSearch($projectId, $keyword, $sortBy, $order);
        $this->assertEquals($count, $requests['length']);
        foreach($requests['results'] as $key => $request){
            $this->assertEquals($expected[$key]['request'], $request['request']);
            $this->assertEquals($expected[$key]['box'], $request['box']);
            $this->assertEquals($expected[$key]['part'], $request['part']);
        }
    }

    /**
     * @return array
     */
    public function todoSearchProvider() : array {
        $this->refreshApplication();
        $project1 = factory(Project::class)->create();
        $box1 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'aaa']);
        $box2 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'bbb']);
        $box3 = factory(Box::class)->create(['project_id' => $project1->id, 'box_name' => 'ccc']);
        $part1 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '2', 'box_id' => $box1->id]);
        $part2 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '3', 'box_id' => $box2->id]);
        $part3 = factory(Part::class)->create(['project_id' => $project1->id, 'part_name' => '1', 'box_id' => $box3->id]);
        $request1 = factory(Request::class)->create(['project_id' => $project1->id, 'is_in_process' => false, 'is_fulfilled' => false, 'request_name' => 'xxxzzz']);
        $request2 = factory(Request::class)->create(['project_id' => $project1->id, 'is_in_process' => false, 'is_fulfilled' => false, 'request_name' => 'yyyeee']);
        $request3 = factory(Request::class)->create(['project_id' => $project1->id, 'is_in_process' => false, 'is_fulfilled' => false, 'request_name' => 'dddxxx']);
        $request1->parts()->attach($part1->id);
        $request2->parts()->attach($part2->id);
        $request3->parts()->attach($part3->id);
        return [[$project1->id, 'xxx', 'box', 'asc', 2, [['request'=>'xxxzzz', 'box'=>'aaa', 'part'=>'2'], ['request'=>'dddxxx', 'box'=>'ccc', 'part'=>'1']]]];
    }

}
