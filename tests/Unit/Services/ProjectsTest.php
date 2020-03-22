<?php

namespace Tests\Unit\Services;

use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Services\ProjectService;
use Faker\Factory as Faker;
use Tests\TestCase;
use DB, Storage;


/**
 * Class ProjectsTest
 * @package Tests\Unit
 */
class ProjectsTest extends TestCase
{
    /**
     * @var ProjectService
     */
    protected $projectService;


    /**
     * @return void
     */
    public function setUp() : void{
        parent::setUp();
        $this->projectService = resolve(ProjectService::class);
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
     * @dataProvider projectsSearchProvider
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @param array $expected
     * @param int $total
     */
    public function testProjectsSearch(string $sortBy, string $order, string $keyword, array $expected, int $total) : void {
        $projects = $this->projectService->projectSearch($sortBy, $order, $keyword);
        $this->assertEquals($total, $projects['total']);
        foreach($projects['result'] as $key => $project){
            $this->assertEquals($project['project_name'], $expected[$key]['project_name']);
            $this->assertEquals($project['company_name'], $expected[$key]['company_name']);
            $this->assertEquals($project['project_owner_name'], $expected[$key]['project_owner_name']);
        }
    }

    /**
     * @return array
     */
    public function projectsSearchProvider() : array {
        $this->refreshApplication();
        $company1 = factory(Company::class)->create(['company_name' => 'aaa']);
        $company2 = factory(Company::class)->create(['company_name' => 'bbb']);
        $company3 = factory(Company::class)->create(['company_name' => 'ccc']);
        $company4 = factory(Company::class)->create(['company_name' => 'ddd']);
        $user1 = factory(User::class)->create(['name' => 'namesearch', 'company_id' => $company1->id]);
        $user2 = factory(User::class)->create(['name' => 'nonefound', 'company_id' => $company2->id]);
        $project1 = factory(Project::class)->create(['project_name' => 'zzz', 'project_owner_id' => $user1->id, 'company_id' => $company1->id]);
        $project2 = factory(Project::class)->create(['project_name' => 'yyy', 'project_owner_id' => $user1->id, 'company_id' => $company2->id]);
        $project3 = factory(Project::class)->create(['project_name' => 'xxx', 'project_owner_id' => $user2->id, 'company_id' => $company3->id]);
        $project4 = factory(Project::class)->create(['project_name' => 'www', 'project_owner_id' => $user1->id, 'company_id' => $company4->id]);
        return [['company_name', 'asc', 'namesearch', [['project_name'=>'zzz', 'company_name'=>'aaa', 'project_owner_name'=>'namesearch'],
            ['project_name'=>'yyy', 'company_name'=>'bbb', 'project_owner_name'=>'namesearch'],
            ['project_name'=>'www', 'company_name'=>'ddd', 'project_owner_name'=>'namesearch']], 3]];
    }
}
