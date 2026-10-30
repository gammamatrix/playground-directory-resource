<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Directory\Resource\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Playground\Directory\Models\Tagged;
use Playground\Directory\Resource\Http\Requests;
use Playground\Directory\Resource\Http\Resources;

/**
 * \Playground\Directory\Resource\Http\Controllers\TaggedController
 */
class TaggedController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'tag_id',
        'model_camel' => 'tagged',
        'model_camels' => 'tagged',
        'model_kebab' => 'tagged',
        'model_kebabs' => 'tagged',
        'model_lower' => 'tagged',
        'model_lowers' => 'tagged',
        'model_label' => 'Tagged',
        'model_labels' => 'Tagged',
        'model_label_plural' => 'Tagged',
        'model_route' => 'playground.directory.resource.tagged',
        'model_route_param' => 'tagged',
        'model_slug' => 'tagged',
        'model_slugs' => 'tagged',
        'model_slug_plural' => 'tagged',
        'model_snake' => 'tagged',
        'model_snakes' => 'tagged',
        'model_studly' => 'Tagged',
        'model_studlies' => 'Tagged',
        'model_variable' => 'tagged',
        'model_variables' => 'tagged',
        'model_variable_plural' => 'tagged',
        'module_label' => 'Directory',
        'module_labels' => 'Directories',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'module_slugs' => 'directories',
        'table' => 'directory_tagged',
        'view' => 'playground-directory-resource',
    ];

    /**
     * Remove the Tagged resource from storage.
     *
     * @route DELETE /resource/directory/tagged/{tagged} playground.directory.resource.tagged.destroy
     */
    public function destroy(
        Tagged $tagged,
        Requests\Tagged\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $tagged->delete();

        if (! $request->expectsJson()) {
            $returnUrl = $validated['_return_url'] ?? '';

            if ($returnUrl && is_string($returnUrl)) {
                return redirect($returnUrl);
            }

            return redirect($this->getRedirectRoute($tagged));
        }

        return response()->noContent();
    }

    /**
     * Store a newly created API Tagged resource in storage.
     *
     * @route POST /resource/directory/tagged playground.directory.resource.tagged.post
     */
    public function store(
        Requests\Tagged\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Tagged {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $tagged = new Tagged($validated);

        $tagged->created_by_id = $user?->id;

        $tagged->save();

        if ($request->expectsJson()) {
            return new Resources\Tagged($tagged)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request)->setStatusCode(201);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect($this->getRedirectRoute($tagged));
    }

    private function getRedirectRoute(Tagged $tagged): string
    {
        $packageInfo = $this->packageInfo();

        $route = $packageInfo->module_route();
        $params = [];

        if (! empty($tagged->task_id)) {
            $params['task'] = $tagged->task_id;
            $route = sprintf(
                '%1$s.tasks.show',
                $packageInfo->module_route()
            );
        } elseif (! empty($tagged->task_list_id)) {
            $params['task_list'] = $tagged->task_list_id;
            $route = sprintf(
                '%1$s.task_lists.show',
                $packageInfo->module_route()
            );
        } elseif (! empty($tagged->task_log_id)) {
            $params['task_log'] = $tagged->task_log_id;
            $route = sprintf(
                '%1$s.task_logs.show',
                $packageInfo->module_route()
            );
        }

        return route($route, $params);
    }
}
