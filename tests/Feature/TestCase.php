<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Directory\Resource;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Playground\Test\OrchestraTestCase;
use Tests\Unit\Playground\Directory\Resource\PackageProviders;

/**
 * \Tests\Feature\Playground\Directory\Resource\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;
    use PackageProviders;

    /**
     * @var array<string, array<string, array<int, string>>>
     */
    protected array $load_migrations = [
        'gammamatrix' => [
            'playground-directory' => [
                // 'migrations',
            ],
        ],
    ];

    protected bool $hasMigrations = true;

    protected bool $load_migrations_laravel = false;

    protected bool $load_migrations_package = false;

    protected bool $load_migrations_playground = true;

    protected bool $setUpUserForPlayground = false;
}
