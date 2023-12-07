<?php

namespace Rocketti\DependecyPattern\Tests;

use Illuminate\Support\Facades\Schema;
// use PHPUnit\Framework\TestCase as UnitTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Rocketti\DependecyPattern\DependencyPatterServiceProvider;

class TestCase extends OrchestraTestCase
{
   
    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('test')->nullable();
            $table->timestamps();
        });
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