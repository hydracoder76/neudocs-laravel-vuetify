<?php
/**
 * User: mlawson
 * Date: 2019-01-23
 * Time: 11:13
 */

namespace Tests\Browser\Requests\Todo;

use NeubusSrm\Models\Org\Project;
use Tests\DuskTestCase;
use NeubusSrm\Models\Auth\User;
use Laravel\Dusk\Browser;

/**
 * Class UploadViewTest
 * @package Tests\Browser\Requests\Todo
 */
class UploadViewTest extends DuskTestCase
{

    /**
     * @throws \Throwable
     */
    public function testNavigateFromTodoPage() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
            'company_id' => $project->company_id
        ]);
        $this->browse(function (Browser $browser) use ($user, $project) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->waitFor('@neu-dropdown')
                ->click('@neu-dropdown')
                ->type('#single-select', $project->project_name)
                ->keys('#single-select',['{enter}'])
                ->assertPresent('@neu-todo-upload-btn')
                ->click('@neu-todo-upload-btn')
                ->assertRouteIs('todo.upload');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testGoToUploadPage() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->assertSee('Upload box files');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testUploadPageHasComponents() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->assertPresent('@neu-upload-component')
                ->assertPresent('@neu-box-part-list-component')
                ->assertPresent('@neu-part-file-list-component');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testUploadPageHasActionButtons() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->assertPresent('@neu-upload-btn');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testUploadPageHasModal() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->click('@neu-btn')
                ->waitFor('@neu-upload-form')
                ->assertSee('Upload files')
                ->assertPresent('@neu-upload-form')
                ->assertPresent('footer .btn-primary')
                ->assertPresent('footer .btn-secondary')
                ->assertPresent('button.close');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testHasDeleteIcon() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->assertPresent('.neu-delete-icon');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testRoleCannotView() {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->assertRouteIs('todo.upload');
        });
    }
}