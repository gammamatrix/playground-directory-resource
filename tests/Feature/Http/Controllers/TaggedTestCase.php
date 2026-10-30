<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Feature\Playground\Directory\Resource\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Playground\Directory\Models\Tagged;

/**
 * \Tests\Feature\Playground\Directory\Resource\Http\Controllers\TaggedTestCase
 */
class TaggedTestCase extends TestCase
{
    /**
     * @var class-string<Model>
     */
    public string $fqdn = Tagged::class;

    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'tag_id',
        'model_camel' => 'tagged',
        'model_camels' => 'tagged',
        'model_kebab' => 'tagged',
        'model_kebabs' => 'tagged',
        'model_lower' => 'tagged',
        'model_lowers' => 'tagged',
        'model_label' => 'Tagged',
        'model_label_plural' => 'Tagged',
        'model_labels' => 'Tagged',
        'model_route' => 'playground.directory.resource.tagged',
        'model_route_param' => 'tagged',
        'model_slug' => 'tagged',
        'model_slugs' => 'tagged',
        'model_slug_plural' => 'tagged',
        'model_snake' => 'tagged',
        'model_snakes' => 'tagged',
        'model_studly' => 'Tagged',
        'model_studlies' => 'Tagged',
        'model_variable' => 'tagged',
        'model_variables' => 'tagged',
        'model_variable_plural' => 'tagged',
        'module_label' => 'Directory',
        'module_label_plural' => 'Directories',
        'module_route' => 'playground.directory.resource',
        'module_slug' => 'directory',
        'module_slugs' => 'directories',
        'privilege' => 'playground-directory-resource:tagged',
        'table' => 'directory_tagged',
        'view' => 'playground.directory.resource::tagged',
    ];

    /**
     * @var array<int, string>
     */
    protected $structure_model = [
        'id',
        'created_by_id',
        'tag_id',
        'location_id',
        'contact_id',
        'created_at',
    ];
}
