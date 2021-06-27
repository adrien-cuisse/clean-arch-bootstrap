<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Fields\Identity\UuidInterface;

/**
 * A business object
 */
interface EntityInterface
{
    /**
     * @return - a unique identity
     */
    public function getUuid(): UuidInterface;
}
