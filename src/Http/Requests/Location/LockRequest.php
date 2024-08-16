<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Directory\Resource\Http\Requests\Location;

use Playground\Directory\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Directory\Resource\Http\Requests\Location\LockRequest
 */
class LockRequest extends FormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        '_return_url' => ['nullable', 'url'],
    ];
}
