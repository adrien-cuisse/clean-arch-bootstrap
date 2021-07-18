<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;

trait Identifiable
{
    private IdentityInterface $identity;

    /**
     * @see IdentifiableInterface
     */
    final public function getIdentity(): IdentityInterface
    {
        return $this->identity;
    }
}
