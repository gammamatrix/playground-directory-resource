<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Directory\Resource\Policies\SublocationPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Directory\Resource\Policies\SublocationPolicy;
use Tests\Unit\Playground\Directory\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Policies\SublocationPolicy\PolicyTest
 */
#[CoversClass(SublocationPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new SublocationPolicy;

        $this->assertInstanceOf(SublocationPolicy::class, $instance);
    }
}
