<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Directory\Resource\Http\Requests\Location;

use Playground\Directory\Resource\Http\Requests\Location\UpdateRequest;
use Tests\Unit\Playground\Directory\Resource\Http\Requests\RequestTestCase;

/**
 * \Tests\Unit\Playground\Directory\Resource\Http\Requests\Location\UpdateRequestTest
 */
class UpdateRequestTest extends RequestTestCase
{
    protected string $requestClass = UpdateRequest::class;

    public function test_UpdateRequest_rules_with_optional_revisions_disabled(): void
    {
        config(['playground-directory-resource.revisions.optional' => false]);
        $instance = new UpdateRequest;
        $rules = $instance->rules();
        $this->assertNotEmpty($rules);
        $this->assertIsArray($rules);
        $this->assertArrayNotHasKey('revision', $rules);
    }

    public function test_UpdateRequest_rules_with_optional_revisions_enabled(): void
    {
        config(['playground-directory-resource.revisions.optional' => true]);
        $instance = new UpdateRequest;
        $rules = $instance->rules();
        $this->assertNotEmpty($rules);
        $this->assertIsArray($rules);
        $this->assertArrayHasKey('revision', $rules);
        $this->assertSame('bool', $rules['revision']);
    }
}
