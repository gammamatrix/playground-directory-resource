<?php
/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Directory Resource Routes: Location
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/directory/location',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {

    Route::get('/{location:slug}', [
        'as' => 'playground.directory.resource.locations.slug',
        'uses' => 'LocationController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/directory/locations',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.directory.resource.locations',
        'uses' => 'LocationController@index',
    ])->can('index', Playground\Directory\Models\Location::class);

    Route::post('/index', [
        'as' => 'playground.directory.resource.locations.index',
        'uses' => 'LocationController@index',
    ])->can('index', Playground\Directory\Models\Location::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.directory.resource.locations.create',
        'uses' => 'LocationController@create',
    ])->can('create', Playground\Directory\Models\Location::class);

    Route::get('/edit/{location}', [
        'as' => 'playground.directory.resource.locations.edit',
        'uses' => 'LocationController@edit',
    ])->whereUuid('location')->can('edit', 'location');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.directory.resource.locations.go',
    //     'uses' => 'LocationController@go',
    // ]);

    Route::get('/{location}', [
        'as' => 'playground.directory.resource.locations.show',
        'uses' => 'LocationController@show',
    ])->whereUuid('location')->can('detail', 'location');

    Route::get('/{location}/revisions', [
        'as' => 'playground.directory.resource.locations.revisions',
        'uses' => 'LocationController@revisions',
    ])->whereUuid('location')->can('revisions', 'location');

    Route::post('/{location}/revisions', [
        'uses' => 'LocationController@revisions',
    ])->whereUuid('location')->can('revisions', 'location');

    Route::get('/revision/{location_revision}', [
        'as' => 'playground.directory.resource.locations.revision',
        'uses' => 'LocationController@revision',
    ])->whereUuid('location')->can('viewRevision', 'location_revision');

    Route::put('/revision/{location_revision}', [
        'as' => 'playground.directory.resource.locations.revision.restore',
        'uses' => 'LocationController@restoreRevision',
    ])->whereUuid('location_revision')->can('restoreRevision', 'location_revision');

    // API

    Route::put('/lock/{location}', [
        'as' => 'playground.directory.resource.locations.lock',
        'uses' => 'LocationController@lock',
    ])->whereUuid('location')->can('lock', 'location');

    Route::delete('/lock/{location}', [
        'as' => 'playground.directory.resource.locations.unlock',
        'uses' => 'LocationController@unlock',
    ])->whereUuid('location')->can('unlock', 'location');

    Route::delete('/{location}', [
        'as' => 'playground.directory.resource.locations.destroy',
        'uses' => 'LocationController@destroy',
    ])->whereUuid('location')->can('delete', 'location')->withTrashed();

    Route::put('/restore/{location}', [
        'as' => 'playground.directory.resource.locations.restore',
        'uses' => 'LocationController@restore',
    ])->whereUuid('location')->can('restore', 'location')->withTrashed();

    Route::post('/', [
        'as' => 'playground.directory.resource.locations.post',
        'uses' => 'LocationController@store',
    ])->can('store', Playground\Directory\Models\Location::class);

    // Route::put('/', [
    //     'as' => 'playground.directory.resource.locations.put',
    //     'uses' => 'LocationController@store',
    // ])->can('store', Playground\Directory\Models\Location::class);
    //
    // Route::put('/{location}', [
    //     'as' => 'playground.directory.resource.locations.put.id',
    //     'uses' => 'LocationController@store',
    // ])->whereUuid('location')->can('update', 'location');

    Route::patch('/{location}', [
        'as' => 'playground.directory.resource.locations.patch',
        'uses' => 'LocationController@update',
    ])->whereUuid('location')->can('update', 'location');
});
