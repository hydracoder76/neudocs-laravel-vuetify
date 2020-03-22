<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-02-27
 * Time: 12:10
 */

namespace Tests\Browser\Login;


use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;

/**
 * Class RedirectAfterLoginTest
 * @package Tests\Browser\Login
 */
class RedirectAfterLoginTest extends DuskTestCase
{

    /**
     * For tests to ensure that the left menu goes to the correct pages with no errors
     * @see \Tests\Browser\Components\BurgerMenuTest
     */
    /**
     * @throws \Throwable
     */
    public function testItLandingPage() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {

            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertPathIs('/admin/users')
                ->assertPresent('@neu-heading')
                ->assertSeeIn('@neu-heading', 'Manage User')
                ->assertPresent('@neu-add-btn')
                ->assertSeeIn('@neu-add-btn', 'Create New User');
        });

    }

    /**
     * @throws \Throwable
     */
    public function testItRemovedAdminHome() : void{
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {

            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertPathIs('/admin/users')
                ->assertPresent('@neu-menu')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->assertDontSeeIn('@neu-menu', 'Admin Home');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testClickOnManageUsersFromIt() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('admin.users')
                ->assertPathIs('/admin/users')
                ->click('@neu-burger-btn-open')
                ->pause(1000)
                ->clickLink('Manage Users')
                ->pause(1000)
                ->assertPresent('@neu-menu')
                ->assertPathIs('/admin/users');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testNeubusLandingPage() : void {

        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)->visitRoute('todo.home')->screenshot('jfapwofijewpfoiwjaefpowaiefj')
                ->assertPathIs('/requests/todo')
                ->assertPresent('@neu-heading')
                ->assertSeeIn('@neu-heading', 'Pending Requests')
                ->assertPresent('@neu-dropdown');
        });
    }

    /**
     * @depends testNeubusLandingPage
     * @throws \Throwable
     */
    public function testRemovedNeubusHome() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)->visit('/')
                ->assertPathIs('/requests/todo');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testClickOnRequestsTodoNeubus() : void {
        $user = factory(User::class)->create([
            'role' => User::ROLE_NEUBUS
        ]);


        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)->visitRoute('todo.home')
                ->assertPathIs('/requests/todo')
            ->assertPresent('@neu-menu')
            ->click('@neu-burger-btn-open')
            ->pause(1000)
            ->assertSeeIn('@neu-menu', 'Request To Do')
            ->clickLink('Request To Do')
            ->pause(1000)
            ->assertPresent('@neu-menu')
            ->assertPathIs('/requests/todo');
        });
    }

}
