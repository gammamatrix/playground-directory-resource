<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Directory\Resource\Http\Requests\Location;

use Tests\Unit\Playground\Directory\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Http\Requests\Location\LockRequestTest
 */
class LockRequestTest extends RequestTestCase
{
    protected string $requestClass = \Playground\Directory\Resource\Http\Requests\Location\LockRequest::class;
}
