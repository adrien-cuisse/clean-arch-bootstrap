<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\UuidV4;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;

final class IdentityFactory implements IdentityFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withIdentity(string $identity): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument('rfcUuidString', $identity);

        return $this;
    }

    public function build(): IdentityInterface
    {
        if ($this->instantiator->hasAssignedConstructorArgument('rfcUuidString')) {
            return UuidV4::fromString(...$this->instantiator->assignedConstructorArguments());
        }

        return new UuidV4;
    }
}
