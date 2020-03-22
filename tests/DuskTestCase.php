<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

/**
 * Class DuskTestCase
 * @package Tests
 * @preserveGlobalState disabled

 */
abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    const USER_PASSWORD = 'foo';
    const USER_NAME = 'Mike Lawson';
    const USER_EMAIL = 'mlawson@neuone.com';

    public function setUp() {
    	parent::setUp();
    	self::prepare();
    }
	/**
     * Prepare for Dusk test execution.
     * @return void
     */
    public static function prepare()
    {
        if (config('srm.env') === 'testing') {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        if (config('srm.env') === 'testing' || $this->app->environment() === 'testing') {
            $options = (new ChromeOptions)->addArguments([
	            '--window-size=1920,1920',
                '--disable-gpu',
                '--headless'

            ]);
            return RemoteWebDriver::create(
                'localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )->setCapability('acceptInsecureCerts', true)
            );
        }
        else {

            return RemoteWebDriver::create(
                'localhost:4444', DesiredCapabilities::phantomjs()
                    ->setCapability('acceptInsecureCerts', true)
            );
        }
    }
}
