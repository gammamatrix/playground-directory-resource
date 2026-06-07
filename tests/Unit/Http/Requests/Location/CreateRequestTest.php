<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Directory\Resource\Http\Requests\Location;

use Playground\Directory\Resource\Http\Requests\Location\CreateRequest;
use Tests\Unit\Playground\Directory\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Http\Requests\Location\CreateRequestTest
 */
class CreateRequestTest extends RequestTestCase
{
    protected string $requestClass = CreateRequest::class;
}
