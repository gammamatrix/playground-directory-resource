<?php

/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Playground\Directory\Models\Tagged;

/*
|--------------------------------------------------------------------------
| Directory Resource Routes: Tagged
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/directory/tagged',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {

    // API

    Route::delete('/{tagged}', [
        'as' => 'playground.directory.resource.tagged.destroy',
        'uses' => 'TaggedController@destroy',
    ])->whereUuid('tagged')->can('delete', 'tagged');

    Route::post('/', [
        'as' => 'playground.directory.resource.tagged.post',
        'uses' => 'TaggedController@store',
    ])->can('store', Tagged::class);
});
