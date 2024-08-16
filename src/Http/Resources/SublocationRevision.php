<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Directory\Resource\Http\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Playground\Directory\Models\SublocationRevision as SublocationRevisionModel;
use Playground\Directory\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Directory\Resource\Http\Resources\SublocationRevision
 */
class SublocationRevision extends JsonResource
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request&FormRequest $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        /**
         * @var ?SublocationRevisionModel $sublocation_revision
         */
        $sublocation_revision = $request->route('sublocation_revision');

        /**
         * @var ?Authenticatable $user;
         */
        $user = $request->user();

        return [
            'meta' => [
                'id' => $sublocation_revision?->id,
                'rules' => $request->rules(),
                'session_user_id' => $user?->getAttributeValue('id'),
                'timestamp' => Carbon::now()->toJson(),
                'validated' => $request->validated(),
            ],
        ];
    }
}
