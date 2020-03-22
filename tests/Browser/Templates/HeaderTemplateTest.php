<?php

namespace Tests\Browser\Templates;

use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

/**
 * Class HeaderTemplateTest
 * @package Tests\Browser
 */
class HeaderTemplateTest extends DuskTestCase
{

    /**
     * @throws \Throwable
     */
    public function testHeaderExists() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertPresent('#neu-header');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testHeaderHasLinks() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertPresent('#neu-header #neu-header-actions')
                ->assertPresent('#neu-header a');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testIconExists() {

        $this->browse(function (Browser $browser) {
            $altAttr = $browser->visitRoute('login.form.view')
                ->attribute('img.neu-logo', 'alt');
            parent::assertEquals('Logo', $altAttr);

            $browser->visitRoute('login.form.view')
                ->assertPresent('img.neu-logo');


        });
    }

    /**
     * @throws \Throwable
     */
    public function testHeaderHasTitle() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertSeeIn('#neu-header', 'Service Request Management')
                ->assertPresent('#neu-header h1');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLoginNotPresentOnLoginView() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertDontSeeIn('#neu-header', 'Login');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserNamePresent() : void {
        $user = factory(User::class)->create([
            'name' => self::USER_NAME,
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->logout()->visitRoute('login.form.view')
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertDontSeeIn('@neu-header-actions', 'Logout')
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->loginAs($user)
                ->visitRoute('it.home')
                ->assertSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Logout')
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->logout();
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserNameNotPresentOnLogout() : void {
        $user = factory(User::class)->create([
            'name' => self::USER_NAME,
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)->visitRoute('it.home')
                ->assertSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertSeeIn('@neu-header-actions', 'Logout')
                ->logout()
                ->visitRoute('login.form.view')
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Contact')
                ->assertDontSeeIn('@neu-header-actions', 'Logout');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUserNameMatchesLoggedInUser() : void {
        $user = factory(User::class)->create([
            'name' => self::USER_NAME,
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)->visitRoute('it.home')
                ->assertDontSeeIn('@neu-header-actions', 'Not Me')
                ->assertSeeIn('@neu-header-actions', self::USER_NAME)
                ->logout();
        });
    }

    /**
     * @throws \Throwable
     */
    public function testDataBaseUserNameMatchesLoggedInUser() : void {
        $user = factory(User::class)->create([
            'name' => 'Not Me',
            'role' => User::ROLE_IT
        ]);

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)->visitRoute('it.home')
                ->assertDontSeeIn('@neu-header-actions', self::USER_NAME)
                ->assertSeeIn('@neu-header-actions', 'Not Me')
                ->logout();
        });
    }
}
