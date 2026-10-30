<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Feature\Playground\Directory\Resource\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Playground\Test\Feature\Http\Controllers\Resource;

/**
 * \Tests\Feature\Playground\Directory\Resource\Http\Controllers\PlaygroundCase
 */
class RevisionTestCase extends PlaygroundCase
{
    use Resource\Playground\RestoreRevisionJsonTrait;
    use Resource\Playground\RestoreRevisionTrait;
    use Resource\Playground\RevisionJsonTrait;
    use Resource\Playground\RevisionsJsonTrait;
    use Resource\Playground\RevisionsTrait;
    use Resource\Playground\RevisionTrait;

    /**
     * @var class-string<Model>
     */
    public string $fqdn = Model::class;

    /**
     * @var class-string<Model>
     */
    public string $fqdnRevision = Model::class;

    public string $revisionId = 'revision_id';

    public string $revisionRouteParameter = 'revision';

    /**
     * @return class-string<Model>
     */
    public function getGetFqdnRevision(): string
    {
        return $this->fqdnRevision;
    }

    public function getRevisionId(): string
    {
        return $this->revisionId;
    }

    public function getRevisionRouteParameter(): string
    {
        return $this->revisionRouteParameter;
    }
}
