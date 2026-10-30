<?php

/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Playground\Directory\Models\Tag;

/*
|--------------------------------------------------------------------------
| Directory Resource Routes: Tag
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/directory/tag',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {

    Route::get('/{tag:slug}', [
        'as' => 'playground.directory.resource.tags.slug',
        'uses' => 'TagController@show',
    ])->where('slug', '[a-zA-Z0-9\-]+');
});

Route::group([
    'prefix' => 'resource/directory/tags',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {
    Route::get('/', [
        'as' => 'playground.directory.resource.tags',
        'uses' => 'TagController@index',
    ])->can('index', Tag::class);

    Route::post('/index', [
        'as' => 'playground.directory.resource.tags.index',
        'uses' => 'TagController@index',
    ])->can('index', Tag::class);

    // UI

    Route::get('/create', [
        'as' => 'playground.directory.resource.tags.create',
        'uses' => 'TagController@create',
    ])->can('create', Tag::class);

    Route::get('/edit/{tag}', [
        'as' => 'playground.directory.resource.tags.edit',
        'uses' => 'TagController@edit',
    ])->whereUuid('tag')->can('edit', 'tag');

    // Route::get('/go/{id}', [
    //     'as' => 'playground.directory.resource.tags.go',
    //     'uses' => 'TagController@go',
    // ]);

    Route::get('/{tag}', [
        'as' => 'playground.directory.resource.tags.show',
        'uses' => 'TagController@show',
    ])->whereUuid('tag')->can('detail', 'tag')->withTrashed();

    // API

    Route::put('/lock/{tag}', [
        'as' => 'playground.directory.resource.tags.lock',
        'uses' => 'TagController@lock',
    ])->whereUuid('tag')->can('lock', 'tag');

    Route::delete('/lock/{tag}', [
        'as' => 'playground.directory.resource.tags.unlock',
        'uses' => 'TagController@unlock',
    ])->whereUuid('tag')->can('unlock', 'tag');

    Route::delete('/{tag}', [
        'as' => 'playground.directory.resource.tags.destroy',
        'uses' => 'TagController@destroy',
    ])->whereUuid('tag')->can('delete', 'tag')->withTrashed();

    Route::put('/restore/{tag}', [
        'as' => 'playground.directory.resource.tags.restore',
        'uses' => 'TagController@restore',
    ])->whereUuid('tag')->can('restore', 'tag')->withTrashed();

    Route::post('/', [
        'as' => 'playground.directory.resource.tags.post',
        'uses' => 'TagController@store',
    ])->can('store', Tag::class);

    // Route::put('/', [
    //     'as' => 'playground.directory.resource.tags.put',
    //     'uses' => 'TagController@store',
    // ])->can('store', Playground\Directory\Models\Tag::class);
    //
    // Route::put('/{tag}', [
    //     'as' => 'playground.directory.resource.tags.put.id',
    //     'uses' => 'TagController@store',
    // ])->whereUuid('tag')->can('update', 'tag');

    Route::patch('/{tag}', [
        'as' => 'playground.directory.resource.tags.patch',
        'uses' => 'TagController@update',
    ])->whereUuid('tag')->can('update', 'tag');
});
