<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface IdentityFactoryInterface extends FactoryInterface
{
    public function withIdentity(string $identity): self;

    public function build(): IdentityInterface;
}
