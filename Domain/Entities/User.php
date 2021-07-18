<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArch\Domain\Traits\Mailable;

/**
 * @see UserInterface
 */
final class User implements UserInterface
{
    use Mailable;

    public function __construct(/*private EntityInterface $entity*/)
    {
        // really ?
    }

    public function getIdentity(): IdentityInterface
    {
        return $this->entity->getIdentity();
    }
}
