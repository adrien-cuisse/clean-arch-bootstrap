<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity;

interface IdentifiableInterface
{
    public function identity(): IdentityInterface;
}
