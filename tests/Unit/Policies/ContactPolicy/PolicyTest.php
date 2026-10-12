<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Directory\Resource\Policies\ContactPolicy;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Directory\Resource\Policies\ContactPolicy;
use Tests\Unit\Playground\Directory\Resource\TestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Policies\ContactPolicy\PolicyTest
 */
#[CoversClass(ContactPolicy::class)]
class PolicyTest extends TestCase
{
    public function test_policy_instance(): void
    {
        $instance = new ContactPolicy;

        /** @phpstan-ignore method.alreadyNarrowedType */
        $this->assertInstanceOf(ContactPolicy::class, $instance);
    }
}
