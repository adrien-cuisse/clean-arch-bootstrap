<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;

interface IdentityFactoryInterface
{
    public function withIdentity(string $identity): self;

    public function build(): IdentityInterface;
}
