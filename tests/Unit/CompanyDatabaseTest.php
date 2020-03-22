<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;
use Tests\TestCase;

/**
 * Class CompanyDatabaseTest
 * @package Tests\Unit
 */
class CompanyDatabaseTest extends TestCase
{

    /**
     * Check the company relationship
     */
    public function testUsersWithCompany() {
        $srmUser = factory(User::class)->create();
        $this->assertDatabaseHas('companies', ['id' => $srmUser->company->id]);
    }

    public function testCompanyUserRelation() {
        $srmUser = factory(User::class)->create();
        $company = Company::whereId($srmUser->company->id)->first();
        $this->assertDatabaseHas('users', ['id' => $company->users->first()->id]);
        $this->assertDatabaseHas('users', ['company_id' => $company->id]);

    }

    public function testCompanyProjectRelation() {
    	$company = factory(Company::class)->create();

    	factory(Project::class, 2)->create([
    		'company_id' => $company->id
	    ]);

    	$this->assertDatabaseHas('companies', ['id' => $company->projects->first()->company_id]);
	    self::assertEquals(2, Project::whereCompanyId($company->id)->count());

    }

    public function testCompanyBoxRelation() {
	    $company = factory(Company::class)->create();
    	factory(Box::class, 4)->create([
    		'company_id' => $company->id
	    ]);
    	$this->assertDatabaseHas('companies', ['id' => $company->boxes->first()->company_id]);
    	self::assertEquals(4, Box::whereCompanyId($company->id)->count());
    }

    public function testCompanyFactory() {
    	factory(Company::class, 10)->create();
    	self::assertEquals(10, count(Company::all()));
    }



}
