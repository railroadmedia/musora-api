<?php

namespace Railroad\MusoraAPi\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Railroad\MusoraApi\Providers\MusoraApiServiceProvider;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->register(MusoraApiServiceProvider::class);
    }
}
