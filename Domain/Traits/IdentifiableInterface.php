<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;

interface IdentifiableInterface
{
    public function getIdentity(): IdentityInterface;
}
