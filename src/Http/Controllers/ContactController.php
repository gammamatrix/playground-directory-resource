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
use Playground\Directory\Models\Contact;
use Playground\Directory\Models\ContactRevision;
use Playground\Directory\Resource\Http\Requests;
use Playground\Directory\Resource\Http\Resources;

/**
 * \Playground\Directory\Resource\Http\Controllers\ContactController
 */
class ContactController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_camel' => 'contact',
        'model_camels' => 'contacts',
        'model_kebab' => 'contact',
        'model_kebabs' => 'contacts',
        'model_lower' => 'contact',
        'model_lowers' => 'contacts',
        'model_label' => 'Contact',
        'model_labels' => 'Contacts',
        'model_label_plural' => 'Contacts',
        'model_route' => 'playground.directory.resource.contacts',
        'model_route_param' => 'contact',
        'model_slug' => 'contact',
        'model_slugs' => 'contacts',
        'model_slug_plural' => 'contacts',
        'model_snake' => 'contact',
        'model_snakes' => 'contacts',
        'model_studly' => 'Contact',
        'model_studlies' => 'Contacts',
        'model_variable' => 'contact',
        'model_variables' => 'contacts',
        'model_variable_plural' => 'contacts',
        'module_label' => 'Directory',
        'module_labels' => 'Directories',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'module_slugs' => 'directories',
        'privilege' => 'playground-directory-resource:contact',
        'table' => 'directory_contacts',
        'view' => 'playground-directory-resource::contact',
    ];

    /**
     * Create the Contact resource in storage.
     *
     * @route GET /resource/directory/contacts/create playground.directory.resource.contacts.create
     */
    public function create(
        Requests\Contact\CreateRequest $request
    ): JsonResponse|View|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $contact = new Contact($validated);

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
            'data' => $contact,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $contact->toArray();

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
     * Edit the Contact resource in storage.
     *
     * @route GET /resource/directory/contacts/edit/{contact} playground.directory.resource.contacts.edit
     */
    public function edit(
        Contact $contact,
        Requests\Contact\EditRequest $request
    ): JsonResponse|View|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $contact->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $contact->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $contact,
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
     * Remove the Contact resource from storage.
     *
     * @route DELETE /resource/directory/contacts/{contact} playground.directory.resource.contacts.destroy
     */
    public function destroy(
        Contact $contact,
        Requests\Contact\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $contact->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $contact->delete();
        } else {
            $contact->forceDelete();
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
     * Lock the Contact resource in storage.
     *
     * @route PUT /resource/directory/contacts/{contact} playground.directory.resource.contacts.lock
     */
    public function lock(
        Contact $contact,
        Requests\Contact\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $contact->modified_by_id = $user->id;
        }

        $contact->locked = true;

        $contact->save();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }

    /**
     * Display a listing of Contact resources.
     *
     * @route GET /resource/directory/contacts playground.directory.resource.contacts
     */
    public function index(
        Requests\Contact\IndexRequest $request
    ): JsonResponse|View|Resources\ContactCollection {

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

        $query = Contact::addSelect(sprintf('%1$s.*', $packageInfo->table()));

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
            return new Resources\ContactCollection($paginator)->response($request);
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
     * Restore the Contact resource from the trash.
     *
     * @route PUT /resource/directory/contacts/restore/{contact} playground.directory.resource.contacts.restore
     */
    public function restore(
        Contact $contact,
        Requests\Contact\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $contact->modified_by_id = $user?->id;

        $contact->restore();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }

    /**
     * Restore the Contact resource from the trash.
     *
     * @route PUT /resource/directory/contacts/revision/{contact_revision} playground.directory.resource.contacts.revision.restore
     */
    public function restoreRevision(
        ContactRevision $contact_revision,
        Requests\Contact\RestoreRevisionRequest $request
    ): JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        /**
         * @var Contact $contact
         */
        $contact = Contact::where(
            'id',
            $contact_revision->contact_id
        )->firstOrFail();

        $this->saveRevision($contact);

        $user = $request->user();

        foreach ($contact->getFillable() as $column) {
            $contact->setAttribute(
                $column,
                $contact_revision->getAttributeValue($column)
            );
        }

        $contact->save();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }

    /**
     * Display the Contact revision.
     *
     * @route GET /resource/directory/contacts/revision/{contact_revision} playground.directory.resource.contacts.revision
     */
    public function revision(
        ContactRevision $contact_revision,
        Requests\Contact\ShowRevisionRequest $request
    ): JsonResponse|View|Resources\ContactRevision {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\ContactRevision($contact_revision)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $contact_revision->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $contact_revision,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/revision', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Display a listing of Contact resources.
     *
     * @route GET /resource/directory/contacts/{contact}/revisions playground.directory.resource.contacts.revisions
     */
    public function revisions(
        Contact $contact,
        Requests\Contact\RevisionsRequest $request
    ): JsonResponse|View|Resources\ContactRevisionCollection {

        $packageInfo = $this->packageInfo();

        $user = $request->user();

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

        $query = $contact->revisions();

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
            return new Resources\ContactRevisionCollection($paginator)->additional(['meta' => [
                'info' => $packageInfo,
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
            'info' => $packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/revisions', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Save a revision of a Contact.
     */
    public function saveRevision(Contact $contact): ContactRevision
    {
        /**
         * @var array<string, mixed> $data
         */
        $data = $contact->toArray();

        $revision = new ContactRevision($data);

        $revision->created_by_id = $contact->created_by_id;
        $revision->modified_by_id = $contact->modified_by_id;
        $revision->owned_by_id = $contact->owned_by_id;
        $revision->contact_id = $contact->id;

        $r = ContactRevision::where('contact_id', $contact->id)->max('revision');
        $r = ! is_numeric($r) || empty($r) || $r < 0 ? 0 : (int) $r;
        $r++;

        $revision->revision = $r;
        $contact->revision = $r;

        $revision->saveOrFail();

        return $revision;
    }

    /**
     * Display the Contact resource.
     *
     * @route GET /resource/directory/contacts/{contact} playground.directory.resource.contacts.show
     */
    public function show(
        Contact $contact,
        Requests\Contact\ShowRequest $request
    ): JsonResponse|View|Resources\Contact {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $contact->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $contact,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Contact resource in storage.
     *
     * @route POST /resource/directory/contacts playground.directory.resource.contacts.post
     */
    public function store(
        Requests\Contact\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $contact = new Contact($validated);

        $contact->created_by_id = $user?->id;

        $contact->save();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }

    /**
     * Unlock the Contact resource in storage.
     *
     * @route DELETE /resource/directory/contacts/lock/{contact} playground.directory.resource.contacts.unlock
     */
    public function unlock(
        Contact $contact,
        Requests\Contact\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $contact->locked = false;

        $contact->modified_by_id = $user?->id;

        $contact->save();

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }

    /**
     * Update the Contact resource in storage.
     *
     * @route PATCH /resource/directory/contacts/{contact} playground.directory.resource.contacts.patch
     */
    public function update(
        Contact $contact,
        Requests\Contact\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Contact {

        $packageInfo = $this->packageInfo();

        $this->saveRevision($contact);

        $validated = $request->validated();

        $user = $request->user();

        $contact->modified_by_id = $user?->id;

        $contact->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Contact($contact)->additional(['meta' => [
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
        ), ['contact' => $contact->id]));
    }
}
