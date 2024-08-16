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
use Playground\Directory\Models\Sublocation;
use Playground\Directory\Models\SublocationRevision;
use Playground\Directory\Resource\Http\Requests;
use Playground\Directory\Resource\Http\Resources;

/**
 * \Playground\Directory\Resource\Http\Controllers\SublocationController
 */
class SublocationController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Sublocation',
        'model_label_plural' => 'Sublocations',
        'model_route' => 'playground.directory.resource.sublocations',
        'model_slug' => 'sublocation',
        'model_slug_plural' => 'sublocations',
        'module_label' => 'Directory',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'privilege' => 'playground-directory-resource:sublocation',
        'table' => 'directory_sublocations',
        'view' => 'playground-directory-resource::sublocation',
    ];

    /**
     * Create the Sublocation resource in storage.
     *
     * @route GET /resource/directory/sublocations/create playground.directory.resource.sublocations.create
     */
    public function create(
        Requests\Sublocation\CreateRequest $request
    ): JsonResponse|View|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        $sublocation = new Sublocation($validated);

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $sublocation,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $sublocation->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        if (! $request->session()->has('errors')) {
            session()->flashInput($flash);
        }

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Edit the Sublocation resource in storage.
     *
     * @route GET /resource/directory/sublocations/edit/{sublocation} playground.directory.resource.sublocations.edit
     */
    public function edit(
        Sublocation $sublocation,
        Requests\Sublocation\EditRequest $request
    ): JsonResponse|View|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $sublocation->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $sublocation->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $sublocation,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Sublocation resource from storage.
     *
     * @route DELETE /resource/directory/sublocations/{sublocation} playground.directory.resource.sublocations.destroy
     */
    public function destroy(
        Sublocation $sublocation,
        Requests\Sublocation\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $sublocation->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $sublocation->delete();
        } else {
            $sublocation->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route($this->packageInfo['model_route']));
    }

    /**
     * Lock the Sublocation resource in storage.
     *
     * @route PUT /resource/directory/sublocations/{sublocation} playground.directory.resource.sublocations.lock
     */
    public function lock(
        Sublocation $sublocation,
        Requests\Sublocation\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $sublocation->modified_by_id = $user->id;
        }

        $sublocation->locked = true;

        $sublocation->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $sublocation->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }

    /**
     * Display a listing of Sublocation resources.
     *
     * @route GET /resource/directory/sublocations playground.directory.resource.sublocations
     */
    public function index(
        Requests\Sublocation\IndexRequest $request
    ): JsonResponse|View|Resources\SublocationCollection {

        $user = $request->user();

        $validated = $request->validated();

        $query = Sublocation::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new Resources\SublocationCollection($paginator))->response($request);
        }

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
            'info' => $this->packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/index', $this->packageInfo['view']), $data);
    }

    /**
     * Restore the Sublocation resource from the trash.
     *
     * @route PUT /resource/directory/sublocations/restore/{sublocation} playground.directory.resource.sublocations.restore
     */
    public function restore(
        Sublocation $sublocation,
        Requests\Sublocation\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $sublocation->modified_by_id = $user->id;
        }

        $sublocation->restore();

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }

    /**
     * Restore the Sublocation resource from the trash.
     *
     * @route PUT /resource/directory/sublocations/revision/{sublocation_revision} playground.directory.resource.sublocations.revision.restore
     */
    public function restoreRevision(
        SublocationRevision $sublocation_revision,
        Requests\Sublocation\RestoreRevisionRequest $request
    ): JsonResponse|RedirectResponse|Resources\Sublocation {
        $validated = $request->validated();

        /**
         * @var Sublocation $sublocation
         */
        $sublocation = Sublocation::where(
            'id',
            $sublocation_revision->sublocation_id
        )->firstOrFail();

        $this->saveRevision($sublocation);

        $user = $request->user();

        foreach ($sublocation->getFillable() as $column) {
            $sublocation->setAttribute(
                $column,
                $sublocation_revision->getAttributeValue($column)
            );
        }

        $sublocation->save();

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }

    /**
     * Display the Sublocation revision.
     *
     * @route GET /resource/directory/sublocations/revision/{sublocation_revision} playground.directory.resource.sublocations.revision
     */
    public function revision(
        SublocationRevision $sublocation_revision,
        Requests\Sublocation\ShowRevisionRequest $request
    ): JsonResponse|View|Resources\SublocationRevision {

        if ($request->expectsJson()) {
            return (new Resources\SublocationRevision($sublocation_revision))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $sublocation_revision->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
            'input' => $request->input(),
        ];

        $data = [
            'data' => $sublocation_revision,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/revision', $this->packageInfo['view']), $data);
    }

    /**
     * Display a listing of Sublocation resources.
     *
     * @route GET /resource/directory/sublocations/{sublocation}/revisions playground.directory.resource.sublocations.revisions
     */
    public function revisions(
        Sublocation $sublocation,
        Requests\Sublocation\RevisionsRequest $request
    ): JsonResponse|View|Resources\SublocationRevisionCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = $sublocation->revisions();

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
            return (new Resources\SublocationRevisionCollection($paginator))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

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
            'info' => $this->packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/revisions', $this->packageInfo['view']), $data);
    }

    /**
     * Save a revision of a Sublocation.
     */
    public function saveRevision(Sublocation $sublocation): SublocationRevision
    {
        $revision = new SublocationRevision($sublocation->toArray());

        $revision->created_by_id = $sublocation->created_by_id;
        $revision->modified_by_id = $sublocation->modified_by_id;
        $revision->owned_by_id = $sublocation->owned_by_id;
        $revision->sublocation_id = $sublocation->id;

        $r = SublocationRevision::where('sublocation_id', $sublocation->id)->max('revision');
        $r = ! is_numeric($r) || empty($r) || $r < 0 ? 0 : (int) $r;
        $r++;

        $revision->revision = $r;
        $sublocation->revision = $r;

        $revision->saveOrFail();

        return $revision;
    }

    /**
     * Display the Sublocation resource.
     *
     * @route GET /resource/directory/sublocations/{sublocation} playground.directory.resource.sublocations.show
     */
    public function show(
        Sublocation $sublocation,
        Requests\Sublocation\ShowRequest $request
    ): JsonResponse|View|Resources\Sublocation {

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $sublocation->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
            'input' => $request->input(),
        ];

        $data = [
            'data' => $sublocation,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Sublocation resource in storage.
     *
     * @route POST /resource/directory/sublocations playground.directory.resource.sublocations.post
     */
    public function store(
        Requests\Sublocation\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        $sublocation = new Sublocation($validated);

        if ($user?->id) {
            $sublocation->created_by_id = $user->id;
        }

        $sublocation->save();

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }

    /**
     * Unlock the Sublocation resource in storage.
     *
     * @route DELETE /resource/directory/sublocations/lock/{sublocation} playground.directory.resource.sublocations.unlock
     */
    public function unlock(
        Sublocation $sublocation,
        Requests\Sublocation\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Sublocation {

        $validated = $request->validated();

        $user = $request->user();

        $sublocation->locked = false;

        if ($user?->id) {
            $sublocation->modified_by_id = $user->id;
        }

        $sublocation->save();

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }

    /**
     * Update the Sublocation resource in storage.
     *
     * @route PATCH /resource/directory/sublocations/{sublocation} playground.directory.resource.sublocations.patch
     */
    public function update(
        Sublocation $sublocation,
        Requests\Sublocation\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Sublocation {

        $this->saveRevision($sublocation);

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $sublocation->modified_by_id = $user->id;
        }

        $sublocation->update($validated);

        if ($request->expectsJson()) {
            return (new Resources\Sublocation($sublocation))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['sublocation' => $sublocation->id]));
    }
}
