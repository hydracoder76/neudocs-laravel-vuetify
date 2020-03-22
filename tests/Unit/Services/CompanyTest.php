<?php

namespace Tests\Unit\Services;

use NeubusSrm\Models\Org\Company;
use NeubusSrm\Services\CompanyService;
use Faker\Factory as Faker;
use Tests\TestCase;
use DB, Storage;


/**
 * Class CompanyTest
 * @package Tests\Unit
 */
class CompanyTest extends TestCase
{
    /**
     * @var CompanyService
     */
    protected $companyService;


    /**
     * @return void
     */
    public function setUp() : void{
        parent::setUp();
        $this->companyService = resolve(CompanyService::class);
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
     * @dataProvider companiesSearchProvider
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @param array $expected
     * @param int $total
     */
    public function testCompaniesSearch(string $sortBy, string $order, string $keyword, array $expected, int $total) : void{
        $companies = $this->companyService->companySearch($sortBy, $order, $keyword);
        $this->assertEquals($total, $companies['total']);
        foreach($companies['result'] as $key => $company){
            $this->assertEquals($company['company_name'], $expected[$key]['company_name']);
        }
    }

    /**
     * @return array
     */
    public function companiesSearchProvider() : array{
        $this->refreshApplication();
        $company1 = factory(Company::class)->create(['company_name' => 'aaaxxx']);
        $company2 = factory(Company::class)->create(['company_name' => 'bbbzzz']);
        $company3 = factory(Company::class)->create(['company_name' => 'cccxxx']);
        $company4 = factory(Company::class)->create(['company_name' => 'dddxxx']);
        return [['company_name', 'asc', 'xxx', [['company_name'=>'aaaxxx'], ['company_name'=>'cccxxx'], ['company_name'=>'dddxxx']], 3]];
    }
}
