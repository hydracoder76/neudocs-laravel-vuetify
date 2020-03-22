<?php

namespace Tests\Browser\Templates;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class FooterTemplateTest
 * @package Tests\Browser
 */
class FooterTemplateTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testTemplateHasFooter() {
        $this->browse(function(Browser $browser) {
            $browser->visitRoute('login.form.view')
                ->assertPresent('#neu-footer');
        });
    }

}
