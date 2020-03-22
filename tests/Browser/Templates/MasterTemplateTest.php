<?php

namespace Tests\Browser\Templates;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class MasterTemplateTest
 * @package Tests\Browser
 */
class MasterTemplateTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testSecurityFeaturesExist() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertPresent('input[type=hidden][name=_token]')
                ->assertSchemeIs('https');

        });
    }

    /**
     * @throws \Throwable
     */
    public function testVueContainerExists() {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login.form.view')
            ->assertPresent('div#app');
        });
    }
}
