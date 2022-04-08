<?php

namespace Rocketti\DependecyPattern\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
// use PHPUnit\Framework\TestCase as UnitTestCase;
use Rocketti\DependecyPattern\DependencyPatterServiceProvider;

class TestCase extends OrchestraTestCase
{
    // protected $loadEnvironmentVariables = true;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            DependencyPatterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}