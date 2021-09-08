<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;

interface IdentifiableInterface
{
    public function identity(): IdentityInterface;
}
