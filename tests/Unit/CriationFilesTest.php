<?php

use Illuminate\Support\Facades\Storage;
use Rocketti\DependecyPattern\Tests\TestCase;

class CriationFilesTest extends TestCase
{
    public function testConsoleCommand()
    {   
        $this->artisan('dp:file Test tests --check')->assertExitCode(0);
    }

    public function testFileWasCreated()
    {
        $this->assertTrue(file_exists('./tests/app/'.env('DEPENDENCY_FOLDER').'/Models/Test.php'));
        $this->assertTrue(file_exists('./tests/app/'.env('DEPENDENCY_FOLDER').'/Repositories/TestRepository.php'));
        $this->assertTrue(file_exists('./tests/app/'.env('DEPENDENCY_FOLDER').'/Services/TestService.php'));
        $this->assertTrue(file_exists('./tests/database/factories/TestFactory.php'));
    }
}
