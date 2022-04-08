<?php

use Rocketti\DependecyPattern\Tests\TestCase;

class CriationFilesTest extends TestCase
{
    public function test_console_command()
    {
        $this->artisan('dp:create Teste teste --check')->assertExitCode(0);
    }
}
