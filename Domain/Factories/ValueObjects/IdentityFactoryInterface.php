<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\IdentityInterface;

interface IdentityFactoryInterface extends FactoryInterface
{
    public function withIdentity(string $identity): self;

    public function build(): IdentityInterface;
}
