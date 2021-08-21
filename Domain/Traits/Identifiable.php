<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;

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
