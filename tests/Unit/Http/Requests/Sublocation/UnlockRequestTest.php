<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Directory\Resource\Http\Requests\Sublocation;

use Playground\Directory\Resource\Http\Requests\Sublocation\UnlockRequest;
use Tests\Unit\Playground\Directory\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Http\Requests\Sublocation\UnlockRequestTest
 */
class UnlockRequestTest extends RequestTestCase
{
    protected string $requestClass = UnlockRequest::class;
}
