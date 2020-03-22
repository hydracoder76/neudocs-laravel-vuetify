<?php

namespace Tests\Browser\Users;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Auth\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class UserAdminScreenTest
 * @package Tests\Browser
 */
class UserAdminScreenTest extends DuskTestCase
{

	use DatabaseMigrations;

    public function testCanSeeManageUserHome()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                    ->visitRoute('admin.users')
                    ->pause(5000)
                    ->click('@neu-heading')
                    ->assertSee('Manage User');
        });
    }

    public function testCanSeeUserTable()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertVisible('@neu-table');
        });
    }

    public function testCanSeeManageEditRowButton()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertVisible('@neu-edit-row');
                });
        });
    }

    public function testCanSeeManageDeleteRowButton()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->pause(5000)
                ->with('@neu-table', function ($table) {
                    $table->assertVisible('@neu-delete-row');
                });
        });
    }

    public function testCanSeeManageAddUserButton()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertVisible('@neu-add-btn');

        });
    }

    public function testCanSeeFilterInput()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertVisible('@neu-filter-input');
        });
    }

    public function testCanSeePagination()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertVisible('@neu-pagination');
        });
    }

    public function testCanSeeAdminRoleInTable()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->pause(5000)
                ->type('@neu-filter-input','admin')
                ->keys('@neu-filter-input',['{enter}'])
                ->with('@neu-table', function ($table) {
                    $table->assertSee('admin');
            });
        });
    }

    public function testCanSeeUserRoleInTable()
    {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
            'company_id' => $project->company_id
        ]);

        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->pause(5000)
                ->type('@neu-filter-input','client')
                ->keys('@neu-filter-input',['{enter}'])
                ->with('@neu-table', function ($table) {
                $table->assertSee('client');
            });
        });
    }
}
