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
use Playground\Directory\Models\Location;
use Playground\Directory\Models\LocationRevision;
use Playground\Directory\Resource\Http\Requests;
use Playground\Directory\Resource\Http\Resources;

/**
 * \Playground\Directory\Resource\Http\Controllers\LocationController
 */
class LocationController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Location',
        'model_label_plural' => 'Locations',
        'model_route' => 'playground.directory.resource.locations',
        'model_slug' => 'location',
        'model_slug_plural' => 'locations',
        'module_label' => 'Directory',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'privilege' => 'playground-directory-resource:location',
        'table' => 'directory_locations',
        'view' => 'playground-directory-resource::location',
    ];

    /**
     * Create the Location resource in storage.
     *
     * @route GET /resource/directory/locations/create playground.directory.resource.locations.create
     */
    public function create(
        Requests\Location\CreateRequest $request
    ): JsonResponse|View|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        $location = new Location($validated);

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
            'data' => $location,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $location->toArray();

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
     * Edit the Location resource in storage.
     *
     * @route GET /resource/directory/locations/edit/{location} playground.directory.resource.locations.edit
     */
    public function edit(
        Location $location,
        Requests\Location\EditRequest $request
    ): JsonResponse|View|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $location->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $location->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $location,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Location resource from storage.
     *
     * @route DELETE /resource/directory/locations/{location} playground.directory.resource.locations.destroy
     */
    public function destroy(
        Location $location,
        Requests\Location\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $location->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $location->delete();
        } else {
            $location->forceDelete();
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
     * Lock the Location resource in storage.
     *
     * @route PUT /resource/directory/locations/{location} playground.directory.resource.locations.lock
     */
    public function lock(
        Location $location,
        Requests\Location\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $location->modified_by_id = $user->id;
        }

        $location->locked = true;

        $location->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $location->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }

    /**
     * Display a listing of Location resources.
     *
     * @route GET /resource/directory/locations playground.directory.resource.locations
     */
    public function index(
        Requests\Location\IndexRequest $request
    ): JsonResponse|View|Resources\LocationCollection {

        $user = $request->user();

        $validated = $request->validated();

        $query = Location::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new Resources\LocationCollection($paginator))->response($request);
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
     * Restore the Location resource from the trash.
     *
     * @route PUT /resource/directory/locations/restore/{location} playground.directory.resource.locations.restore
     */
    public function restore(
        Location $location,
        Requests\Location\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $location->modified_by_id = $user->id;
        }

        $location->restore();

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }

    /**
     * Restore the Location resource from the trash.
     *
     * @route PUT /resource/directory/locations/revision/{location_revision} playground.directory.resource.locations.revision.restore
     */
    public function restoreRevision(
        LocationRevision $location_revision,
        Requests\Location\RestoreRevisionRequest $request
    ): JsonResponse|RedirectResponse|Resources\Location {
        $validated = $request->validated();

        /**
         * @var Location $location
         */
        $location = Location::where(
            'id',
            $location_revision->location_id
        )->firstOrFail();

        $this->saveRevision($location);

        $user = $request->user();

        foreach ($location->getFillable() as $column) {
            $location->setAttribute(
                $column,
                $location_revision->getAttributeValue($column)
            );
        }

        $location->save();

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }

    /**
     * Display the Location revision.
     *
     * @route GET /resource/directory/locations/revision/{location_revision} playground.directory.resource.locations.revision
     */
    public function revision(
        LocationRevision $location_revision,
        Requests\Location\ShowRevisionRequest $request
    ): JsonResponse|View|Resources\LocationRevision {

        if ($request->expectsJson()) {
            return (new Resources\LocationRevision($location_revision))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $location_revision->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
            'input' => $request->input(),
        ];

        $data = [
            'data' => $location_revision,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/revision', $this->packageInfo['view']), $data);
    }

    /**
     * Display a listing of Location resources.
     *
     * @route GET /resource/directory/locations/{location}/revisions playground.directory.resource.locations.revisions
     */
    public function revisions(
        Location $location,
        Requests\Location\RevisionsRequest $request
    ): JsonResponse|View|Resources\LocationRevisionCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = $location->revisions();

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
            return (new Resources\LocationRevisionCollection($paginator))->additional(['meta' => [
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
     * Save a revision of a Location.
     */
    public function saveRevision(Location $location): LocationRevision
    {
        $revision = new LocationRevision($location->toArray());

        $revision->created_by_id = $location->created_by_id;
        $revision->modified_by_id = $location->modified_by_id;
        $revision->owned_by_id = $location->owned_by_id;
        $revision->location_id = $location->id;

        $r = LocationRevision::where('location_id', $location->id)->max('revision');
        $r = ! is_numeric($r) || empty($r) || $r < 0 ? 0 : (int) $r;
        $r++;

        $revision->revision = $r;
        $location->revision = $r;

        $revision->saveOrFail();

        return $revision;
    }

    /**
     * Display the Location resource.
     *
     * @route GET /resource/directory/locations/{location} playground.directory.resource.locations.show
     */
    public function show(
        Location $location,
        Requests\Location\ShowRequest $request
    ): JsonResponse|View|Resources\Location {

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $location->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
            'input' => $request->input(),
        ];

        $data = [
            'data' => $location,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Location resource in storage.
     *
     * @route POST /resource/directory/locations playground.directory.resource.locations.post
     */
    public function store(
        Requests\Location\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        $location = new Location($validated);

        if ($user?->id) {
            $location->created_by_id = $user->id;
        }

        $location->save();

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }

    /**
     * Unlock the Location resource in storage.
     *
     * @route DELETE /resource/directory/locations/lock/{location} playground.directory.resource.locations.unlock
     */
    public function unlock(
        Location $location,
        Requests\Location\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Location {

        $validated = $request->validated();

        $user = $request->user();

        $location->locked = false;

        if ($user?->id) {
            $location->modified_by_id = $user->id;
        }

        $location->save();

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }

    /**
     * Update the Location resource in storage.
     *
     * @route PATCH /resource/directory/locations/{location} playground.directory.resource.locations.patch
     */
    public function update(
        Location $location,
        Requests\Location\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Location {

        $this->saveRevision($location);

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $location->modified_by_id = $user->id;
        }

        $location->update($validated);

        if ($request->expectsJson()) {
            return (new Resources\Location($location))->additional(['meta' => [
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
        ), ['location' => $location->id]));
    }
}
