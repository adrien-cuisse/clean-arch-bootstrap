<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;

interface HasIdentityInterface
{
    public function getIdentity(): IdentityInterface;
}
