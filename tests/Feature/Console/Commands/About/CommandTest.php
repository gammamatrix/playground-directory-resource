<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Directory\Resource\Console\Commands\About;

use Illuminate\Console\Command;
use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Directory\Resource\ServiceProvider;
use Tests\Feature\Playground\Directory\Resource\TestCase;

/**
 * \Tests\Feature\Playground\Directory\Resource\Console\Commands\About\CommandTest
 */
#[CoversClass(ServiceProvider::class)]
class CommandTest extends TestCase
{
    public function test_command_about_displays_package_information_and_succeed(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('about');
        $result->assertExitCode(Command::SUCCESS);
        $result->expectsOutputToContain('Playground: Directory Resource');
    }
}
