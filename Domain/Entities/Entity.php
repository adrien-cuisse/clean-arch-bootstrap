<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Fields\Identity\UuidInterface;
use Alphonse\CleanArch\Domain\Fields\Identity\UuidV4;

/**
 * @see EntityInterface
 */
abstract class Entity implements EntityInterface
{
    private UuidInterface $uuid;

    final public function getUuid(): UuidInterface
    {
        if (isset($this->uuid) === false) {
            $this->uuid = new UuidV4;
        }

        return $this->uuid;
    }
}
