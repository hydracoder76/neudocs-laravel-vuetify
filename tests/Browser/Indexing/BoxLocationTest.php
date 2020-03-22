<?php

namespace Tests\Browser\Indexing;

use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;

/**
 * Class NeubusComponentTest
 * @package Tests\Browser\Login
 */
class BoxLocationTest extends DuskTestCase
{

    /**
     * @throws \Throwable
     */
    public function testMustSeeLoginForBoxLocationWithoutLogin() : void {


        $this->browse(function (Browser $browser) {
            $browser->visitRoute('box.updatelocation')
                ->pause(1000)
                ->assertVisible('@login-form');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeBoxNameAfterLoginAndBoxLocation() : void {

        $srmUser = factory(User::class)->create([
            'email' => DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPathIs('/box/updatelocation')
                ->assertSeeIn('@neu-heading', 'Update Location')
                ->assertSee('Activity')
                ->assertSee('Location')
                ->assertDontSee('No box found by that name')
                ->assertDontSee('You must give')
                ->assertDontSee('New Location Successful')
            ->logout();
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanErrorAlertWhenBlankInputAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'You must give')
                ->assertDontSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSee('New Location Successful');
        });
    }


    /**
     * @throws \Throwable
     */
    public function testCanErrorAlertWhenBlankLocationAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@box-name', $this->box->box_name)
                ->assertValue('@box-name', $this->box->box_name)
                ->type('@activity', 'NCHECKOUT')
                ->assertValue('@activity', 'NCHECKOUT')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'You must give a location info for this entry')
                ->assertDontSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSee('New Location Successful')
                ->pause(5000)
                ->type('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'New Location Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeErrorAlertWhenBoxNameBlankAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@activity', 'NCHECKOUT')
                ->assertValue('@activity', 'NCHECKOUT')
                ->type('@location', '1')
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'You must give')
                ->assertDontSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSee('New Location Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeErrorAlertWhenActivityBlankAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@box-name', $this->box->box_name)
                ->assertValue('@box-name', $this->box->box_name)
                ->type('@location', '1')
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'You must give')
                ->assertDontSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSeeIn('@statusCRUD', 'New Location Successful')
                ->pause(5000)
                ->type('@activity', 'NCHECKOUT')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'New Location Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanErrorAlertWhenNoBoxFoundAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->type('@box-name', 'bla')
                ->assertValue('@box-name', 'bla')
                ->type('@activity', 'NCHECKOUT')
                ->assertValue('@activity', 'NCHECKOUT')
                ->type('@location', '1')
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSeeIn('@statusCRUD', 'No box found by that name')
                ->assertDontSee('New Location Successful');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testCanSeeSuccessAlertWhenInputAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@box-name', $this->box->box_name)
                ->assertValue('@box-name', $this->box->box_name)
                ->type('@activity', 'NCHECKOUT')
                ->assertValue('@activity', 'NCHECKOUT')
                ->type('@location', '1')
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'New Location Successful');
        });
    }

    public function testCanSeeSuccessAlertWhenInputCheckinAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@box-name', $this->box->box_name)
                ->assertValue('@box-name', $this->box->box_name)
                ->type('@activity', 'NCHECKIN')
                ->assertValue('@activity', 'NCHECKIN')
                ->type('@location', '1')
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'New Location Successful');
        });
    }

    public function testCanSeeSuccessAlertWhenInputCheckinUsingTabAndDone() : void {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('box.updatelocation')
                ->assertDontSee('yyy')
                ->type('@box-name', $this->box->box_name)
                ->keys('@box-name', ['{tab}'])
                ->assertValue('@box-name', $this->box->box_name)
                ->type('@activity', 'NCHECKIN')
                ->keys('@activity', ['{tab}'])
                ->assertValue('@activity', 'NCHECKIN')
                ->type('@location', '1')
                ->keys('@location', ['{tab}'])
                ->assertValue('@location', '1')
                ->click('@neu-location-save-btn')
                ->waitFor('@statusCRUD')
                ->assertSeeIn('@statusCRUD', 'New Location Successful');
        });
    }
}
