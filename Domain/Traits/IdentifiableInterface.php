<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\IdentityInterface;

interface IdentifiableInterface
{
    public function identity(): IdentityInterface;
}
