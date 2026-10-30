<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Directory\Resource\Policies;

use Playground\Auth\Policies\ModelPolicy;

/**
 * \Playground\Directory\Resource\Policies\TaggedPolicy
 */
class TaggedPolicy extends ModelPolicy
{
    protected string $package = 'playground-directory-resource';

    /**
     * @var array<int, string> The roles allowed to view the MVC.
     */
    protected $rolesToView = [
        'user',
        'staff',
        'publisher',
        'manager',
        'admin',
        'root',
    ];

    /**
     * @var array<int, string> The roles allowed for actions in the MVC.
     */
    protected $rolesForAction = [
        'publisher',
        'manager',
        'admin',
        'root',
    ];
}
