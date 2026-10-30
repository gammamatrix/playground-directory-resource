<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Directory\Resource\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Playground\Directory\Models\Tag;
use Playground\Directory\Resource\Http\Requests;
use Playground\Directory\Resource\Http\Resources;

/**
 * \Playground\Directory\Resource\Http\Controllers\TagController
 */
class TagController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_camel' => 'tag',
        'model_camels' => 'tags',
        'model_kebab' => 'tag',
        'model_kebabs' => 'tags',
        'model_lower' => 'tag',
        'model_lowers' => 'tags',
        'model_label' => 'Tag',
        'model_labels' => 'Tags',
        'model_label_plural' => 'Tags',
        'model_route' => 'playground.directory.resource.tags',
        'model_route_param' => 'tag',
        'model_slug' => 'tag',
        'model_slugs' => 'tags',
        'model_slug_plural' => 'tags',
        'model_snake' => 'tag',
        'model_snakes' => 'tags',
        'model_studly' => 'Tag',
        'model_studlies' => 'Tags',
        'model_variable' => 'tag',
        'model_variables' => 'tags',
        'model_variable_plural' => 'tags',
        'module_label' => 'Directory',
        'module_labels' => 'Directories',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'module_slugs' => 'directories',
        'privilege' => 'playground-directory-resource:tag',
        'table' => 'directory_tags',
        'view' => 'playground-directory-resource::tag',
    ];

    /**
     * Create the Tag resource in storage.
     *
     * @route GET /resource/directory/tags/create playground.directory.resource.tags.create
     */
    public function create(
        Requests\Tag\CreateRequest $request
    ): JsonResponse|View|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $tag = new Tag($validated);

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $tag,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $tag->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        if (! $request->session()->has('errors')) {
            session()->flashInput($flash);
        }

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/form', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Edit the Tag resource in storage.
     *
     * @route GET /resource/directory/tags/edit/{tag} playground.directory.resource.tags.edit
     */
    public function edit(
        Tag $tag,
        Requests\Tag\EditRequest $request
    ): JsonResponse|View|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $tag->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $tag->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $tag,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        if (! empty($validated['_return_url'])) {
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/form', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Remove the Tag resource from storage.
     *
     * @route DELETE /resource/directory/tags/{tag} playground.directory.resource.tags.destroy
     */
    public function destroy(
        Tag $tag,
        Requests\Tag\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $tag->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $tag->delete();
        } else {
            $tag->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route($packageInfo->model_route()));
    }

    /**
     * Lock the Tag resource in storage.
     *
     * @route PUT /resource/directory/tags/{tag} playground.directory.resource.tags.lock
     */
    public function lock(
        Tag $tag,
        Requests\Tag\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $tag->modified_by_id = $user->id;
        }

        $tag->locked = true;

        $tag->save();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['tag' => $tag->id]));
    }

    /**
     * Display a listing of Tag resources.
     *
     * @route GET /resource/directory/tags playground.directory.resource.tags
     */
    public function index(
        Requests\Tag\IndexRequest $request
    ): JsonResponse|View|Resources\TagCollection {

        $packageInfo = $this->packageInfo();

        /**
         * @var array{
         *     sort: string|array<mixed>,
         *     filter: array{
         *         trash: string
         *     },
         *     perPage: int
         * } $validated
         */
        $validated = $request->validated();

        $query = Tag::addSelect(sprintf('%1$s.*', $packageInfo->table()));

        $query->sort($validated['sort'] ?? null);

        if (! empty($validated['filter']) && is_array($validated['filter'])) {

            $query->filterTrash($validated['filter']['trash'] ?? null);

            $query->filterIds(
                $request->getPaginationIds(),
                $validated
            );

            $query->filterFlags(
                $request->getPaginationFlags(),
                $validated
            );

            $query->filterDates(
                $request->getPaginationDates(),
                $validated
            );

            $query->filterColumns(
                $request->getPaginationColumns(),
                $validated
            );
        }

        $perPage = ! empty($validated['perPage']) && is_int($validated['perPage']) ? $validated['perPage'] : null;
        $paginator = $query->paginate($perPage);

        $paginator->appends($validated);

        if ($request->expectsJson()) {
            return new Resources\TagCollection($paginator)->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'columns' => $request->getPaginationColumns(),
            'dates' => $request->getPaginationDates(),
            'flags' => $request->getPaginationFlags(),
            'ids' => $request->getPaginationIds(),
            'rules' => $request->rules(),
            'sortable' => $request->getSortable(),
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/index', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Restore the Tag resource from the trash.
     *
     * @route PUT /resource/directory/tags/restore/{tag} playground.directory.resource.tags.restore
     */
    public function restore(
        Tag $tag,
        Requests\Tag\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $tag->modified_by_id = $user?->id;

        $tag->restore();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['tag' => $tag->id]));
    }

    /**
     * Display the Tag resource.
     *
     * @route GET /resource/directory/tags/{tag} playground.directory.resource.tags.show
     */
    public function show(
        Tag $tag,
        Requests\Tag\ShowRequest $request
    ): JsonResponse|View|Resources\Tag {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $tag->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $tag,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Tag resource in storage.
     *
     * @route POST /resource/directory/tags playground.directory.resource.tags.post
     */
    public function store(
        Requests\Tag\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $tag = new Tag($validated);

        $tag->created_by_id = $user?->id;

        $tag->save();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request)->setStatusCode(201);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['tag' => $tag->id]));
    }

    /**
     * Unlock the Tag resource in storage.
     *
     * @route DELETE /resource/directory/tags/lock/{tag} playground.directory.resource.tags.unlock
     */
    public function unlock(
        Tag $tag,
        Requests\Tag\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $tag->locked = false;

        $tag->modified_by_id = $user?->id;

        $tag->save();

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['tag' => $tag->id]));
    }

    /**
     * Update the Tag resource in storage.
     *
     * @route PATCH /resource/directory/tags/{tag} playground.directory.resource.tags.patch
     */
    public function update(
        Tag $tag,
        Requests\Tag\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Tag {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $tag->modified_by_id = $user?->id;

        $tag->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Tag($tag)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['tag' => $tag->id]));
    }
}
