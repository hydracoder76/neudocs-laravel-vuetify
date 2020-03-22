<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-02-26
 * Time: 10:04
 */

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;

class BurgerMenuTest extends DuskTestCase
{


    /**
     * test to make sure NSN-670 is satified in removing the client menu times from NSN-760
     * @throws \Throwable
     */
    public function testClientUserNoDataEntry() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('client.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Data Entry');

        });
    }

    /**
     * test to make sure NSN-670 is satified in removing the client menu times from NSN-760
     * @throws \Throwable
     */
    public function testClientUserNoCompleted() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('client.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Request Completed');

        });
    }

    /**
     * test to make sure the todo option is hidden for client users
     * @throws \Throwable
     */
    public function testClientUserNoTodo() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('client.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Request To Do');

        });
    }

    // TODO: when we get to that spot, add a test to make sure going to the todo page directly throws a 403

    /**
     * ensure that all items the user is supposed to see are still there,
     * for the purpose of clients this is: todo, request, copmleted
     * @throws \Throwable
     */
    public function testClientUserRightAllMenu() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('client.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertSeeIn('@neu-menu', 'Requests');

        });

    }

    /**
     * ensure that as a result of this removal, the client cannot see other menu items they should not see all at once
     * which includes: admin, project, company, settings, and user
     */
    public function testClientUserWrongAllMenu() : void {

        $user = factory(User::class)->create([
            'role' => User::ROLE_CLIENT
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('client.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Indexing Configuration')
                ->assertDontSeeIn('@neu-menu', 'Project Management')
                ->assertDontSeeIn('@neu-menu', 'Company Configuration')
                ->assertDontSeeIn('@neu-menu', 'Global Settings')
                ->assertDontSeeIn('@neu-menu', 'Manage Users');

        });
    }

    /**
     * ensure client admin can see the items they're supposed to see
     * @throws \Throwable
     */
    public function testClientAdminUserActual(): void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admin.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertSeeIn('@neu-menu', 'Requests')
                ->assertSeeIn('@neu-menu', 'Manage Users');

        });
    }

    /**
     * Ensure that a client admin does not see what they are not supposed to
     * @throws \Throwable
     */
    public function testClientAdminUserNone() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admin.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Request To Do')
                ->assertDontSeeIn('@neu-menu', 'Request Completed')
                ->assertDontSeeIn('@neu-menu', 'Project Management')
                ->assertDontSeeIn('@neu-menu', 'Indexing Configuration')
                ->assertDontSeeIn('@neu-menu', 'Data Entry')
                ->assertDontSeeIn('@neu-menu', 'Company Configuration')
                ->assertDontSeeIn('@neu-menu', 'Global Settings');

        });
    }

    /**
     * ensure neubus admin can see the items they're supposed to see
     * @throws \Throwable
     */
    public function testNeubusAdminUserActual(): void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertSeeIn('@neu-menu', 'Request To Do')
                ->assertSeeIn('@neu-menu', 'Request Completed')
                ->assertSeeIn('@neu-menu', 'Data Entry');

        });
    }

    /**
     * Ensure that a neubus admin does not see what they are not supposed to
     * @throws \Throwable
     */
    public function testNeubusAdminUserNone() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Requests')
                ->assertDontSeeIn('@neu-menu', 'Admin Home')
                ->assertDontSeeIn('@neu-menu', 'Project Management')
                ->assertDontSeeIn('@neu-menu', 'Indexing Configuration')
                ->assertDontSeeIn('@neu-menu', 'Company Configuration')
                ->assertDontSeeIn('@neu-menu', 'Global Settings')
                ->assertDontSeeIn('@neu-menu', 'Manage Users');

        });
    }

    // test actual navigations to all allowed pages such that 403s are not thrown
    // currently client users have no links to actually click on that could generate these
    // basically, if you click a link and it 403s, at present the menu will not appear

    /**
     * Click all available links and ensure the menu is still present. If it isn't, we know we didn't get to the right
     * place, in this context
     * @throws \Throwable
     */
    public function testClientAdminNoForbidden() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admin.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Manage Users')
                ->pause(1000)
                ->assertPresent('@neu-menu')
                ->visitRoute('admin.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Requests')
                ->pause(1000)
                ->assertPresent('@neu-menu');

        });
    }

    /**
     * Ensure that any available links for a NeubusAdmin exist
     * @throws \Throwable
     * thrown when the menu is not found
     */
    public function testNeubusAdminNoForbidden(): void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Request To Do')
                ->pause(1000)
                ->assertPresent('@neu-menu')
                ->visitRoute('todo.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Request Completed')
                ->pause(1000)
                ->assertPresent('@neu-menu')
                ->visitRoute('todo.home')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Data Entry')
                ->pause(1000)
                ->assertPresent('@neu-menu');

        });
    }

    /**
     * Ensure that all available links to an IT user do not throw a 403
     * @throws \Throwable
     */
    public function testItNoForbidden() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function (Browser $browser) use ($user) {

            $this->itTestsForCompanyConfiguration($browser, $user);
            $this->itTestsForGlobalSettings($browser, $user);
            $this->itTestsForIndexing($browser, $user);
            $this->itTestsForManageUsers($browser, $user);
            $this->itTestsForProjectManagement($browser, $user);
            $this->itTestsForRequestCompleted($browser, $user);
            $this->itTestsForRequestDataEntry($browser, $user);
            $this->itTestsForRequestTodo($browser, $user);

        });
    }

    // all the it tests would make this super long, lets just split it up


    /**
     * @param Browser $browser
     * @param User $user\
     */
    public function itTestsForIndexing(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Indexing Configuration')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForProjectManagement(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Project Management')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForCompanyConfiguration(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Company Configuration')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForGlobalSettings(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Global Settings')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForRequests(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->pause(1000)
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Requests')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForRequestDataEntry(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Data Entry')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForRequestTodo(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Request To Do')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForRequestCompleted(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Request Completed')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

    /**
     * @param Browser $browser
     * @param User $user
     */
    public function itTestsForManageUsers(Browser $browser, User $user) : void {

        $browser->loginAs($user)->visitRoute('it.home')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->clickLink('Manage Users')
            ->pause(1000)
            ->assertPresent('@neu-menu');
    }

}
