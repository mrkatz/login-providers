<?php

namespace Mrkatz\LoginProviders\Tests;

use Mrkatz\LoginProviders\LoginProvidersServiceProvider;
use Orchestra\Testbench\TestCase;

class InitialTest extends TestCase
{
    /**
     * Load package service provider.
     * @param \Illuminate\Foundation\Application $app
     * @return LoginProvidersServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [LoginProvidersServiceProvider::class];
    }

    /** @test */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}