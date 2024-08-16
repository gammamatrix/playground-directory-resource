<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Directory\Resource\Http\Controllers;

use Illuminate\View\View;

/**
 * \Playground\Directory\Resource\Http\Controllers\IndexController
 */
class IndexController extends Controller
{
    /**
     * Show the index.
     */
    public function index(): View
    {
        return view('playground-directory-resource::index');
    }
}
