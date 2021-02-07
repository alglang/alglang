<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        // static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--ignore-ssl-errors',
            '--whitelisted-ips=""',
            '--window-size=1920,1080',
        ]);

        $url = 'http://localhost:9515';

        if (env('USE_SELENIUM', 'false') == 'true') {
            $url = 'http://selenium:4444/wd/hub';
        }

        return RemoteWebDriver::create(
            $url,
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * @param Collection $browsers
     */
    protected function captureFailuresFor($browsers)
    {
        $browsers->each(function ($browser, $key) {
            if (property_exists($browser, 'fitOnFailure') && $browser->fitOnFailure) {
                $browser->fitContent();
            }

            $name = $this->getCallerName();

            $browser->screenshot('failure-'.$name.'-'.$key);
            $browser->storeSource('failure-'.$name.'-'.$key);
        });
    }
}
