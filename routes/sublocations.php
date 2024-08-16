<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Directory Resource Routes: Sublocation
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/directory/sublocation',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {

    Route::get('/{sublocation:slug}', [
        'as' => 'playground.directory.resource.sublocations.slug',
        'uses' => 'SublocationController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/directory/sublocations',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.directory.resource.sublocations',
        'uses' => 'SublocationController@index',
    ])->can('index', Playground\Directory\Models\Sublocation::class);

    Route::post('/index', [
        'as' => 'playground.directory.resource.sublocations.index',
        'uses' => 'SublocationController@index',
    ])->can('index', Playground\Directory\Models\Sublocation::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.directory.resource.sublocations.create',
        'uses' => 'SublocationController@create',
    ])->can('create', Playground\Directory\Models\Sublocation::class);

    Route::get('/edit/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.edit',
        'uses' => 'SublocationController@edit',
    ])->whereUuid('sublocation')->can('edit', 'sublocation');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.directory.resource.sublocations.go',
    //     'uses' => 'SublocationController@go',
    // ]);

    Route::get('/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.show',
        'uses' => 'SublocationController@show',
    ])->whereUuid('sublocation')->can('detail', 'sublocation');

    Route::get('/{sublocation}/revisions', [
        'as' => 'playground.directory.resource.sublocations.revisions',
        'uses' => 'SublocationController@revisions',
    ])->whereUuid('sublocation')->can('revisions', 'sublocation');

    Route::post('/{sublocation}/revisions', [
        'uses' => 'SublocationController@revisions',
    ])->whereUuid('sublocation')->can('revisions', 'sublocation');

    Route::get('/revision/{sublocation_revision}', [
        'as' => 'playground.directory.resource.sublocations.revision',
        'uses' => 'SublocationController@revision',
    ])->whereUuid('sublocation')->can('viewRevision', 'sublocation_revision');

    Route::put('/revision/{sublocation_revision}', [
        'as' => 'playground.directory.resource.sublocations.revision.restore',
        'uses' => 'SublocationController@restoreRevision',
    ])->whereUuid('sublocation_revision')->can('restoreRevision', 'sublocation_revision');

    // API

    Route::put('/lock/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.lock',
        'uses' => 'SublocationController@lock',
    ])->whereUuid('sublocation')->can('lock', 'sublocation');

    Route::delete('/lock/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.unlock',
        'uses' => 'SublocationController@unlock',
    ])->whereUuid('sublocation')->can('unlock', 'sublocation');

    Route::delete('/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.destroy',
        'uses' => 'SublocationController@destroy',
    ])->whereUuid('sublocation')->can('delete', 'sublocation')->withTrashed();

    Route::put('/restore/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.restore',
        'uses' => 'SublocationController@restore',
    ])->whereUuid('sublocation')->can('restore', 'sublocation')->withTrashed();

    Route::post('/', [
        'as' => 'playground.directory.resource.sublocations.post',
        'uses' => 'SublocationController@store',
    ])->can('store', Playground\Directory\Models\Sublocation::class);

    // Route::put('/', [
    //     'as' => 'playground.directory.resource.sublocations.put',
    //     'uses' => 'SublocationController@store',
    // ])->can('store', Playground\Directory\Models\Sublocation::class);
    //
    // Route::put('/{sublocation}', [
    //     'as' => 'playground.directory.resource.sublocations.put.id',
    //     'uses' => 'SublocationController@store',
    // ])->whereUuid('sublocation')->can('update', 'sublocation');

    Route::patch('/{sublocation}', [
        'as' => 'playground.directory.resource.sublocations.patch',
        'uses' => 'SublocationController@update',
    ])->whereUuid('sublocation')->can('update', 'sublocation');
});
