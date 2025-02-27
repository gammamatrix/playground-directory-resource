<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Directory\Resource\Http\Requests\Sublocation;

use Playground\Directory\Resource\Http\Requests\FormRequest;

/**
 * \Playground\Directory\Resource\Http\Requests\Sublocation\DestroyRequest
 */
class DestroyRequest extends FormRequest
{
    /**
     * @var array<string, string|array<mixed>>
     */
    public const RULES = [
        '_return_url' => ['nullable', 'url'],
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $user = $this->user();

        if ($this->userHasAdminPrivileges($user)) {
            $rules['force'] = ['boolean'];
        }

        return $rules;
    }
}
