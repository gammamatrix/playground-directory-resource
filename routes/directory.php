<?php

/**
 * Playground
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Playground\Directory\Models\Location;

/*
|--------------------------------------------------------------------------
| Directory Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group([
    'prefix' => 'resource/directory',
    'middleware' => config('playground-directory-resource.middleware.default'),
    'namespace' => '\Playground\Directory\Resource\Http\Controllers',
], function () {

    Route::get('/', [
        'as' => 'playground.directory.resource',
        'uses' => 'IndexController@index',
    ])->can('index', Location::class);

});
