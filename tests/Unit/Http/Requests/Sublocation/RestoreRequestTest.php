<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Directory\Resource\Http\Requests\Sublocation;

use Playground\Directory\Resource\Http\Requests\Sublocation\RestoreRequest;
use Tests\Unit\Playground\Directory\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Http\Requests\Sublocation\RestoreRequestTest
 */
class RestoreRequestTest extends RequestTestCase
{
    protected string $requestClass = RestoreRequest::class;
}
