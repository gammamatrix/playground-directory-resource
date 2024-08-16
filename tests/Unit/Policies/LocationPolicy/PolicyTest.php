<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Directory\Resource\Policies\LocationPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Directory\Resource\Policies\LocationPolicy;
use Tests\Unit\Playground\Directory\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Policies\LocationPolicy\PolicyTest
 */
#[CoversClass(LocationPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new LocationPolicy;

        $this->assertInstanceOf(LocationPolicy::class, $instance);
    }
}
