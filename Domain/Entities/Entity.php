<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArch\Domain\Traits\Identifiable;

/**
 * @see EntityInterface
 */
final class Entity implements EntityInterface
{
    // use Identifiable;

    public function __construct(private IdentityInterface $interface)
    {
    }
}
