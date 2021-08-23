<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\UuidV4;

final class IdentityFactory implements IdentityFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withIdentity(string $identity): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            name: 'rfcUuidString',
            value: $identity
        );

        return $this;
    }

    public function build(): IdentityInterface
    {
        if ($this->instantiator->hasAssignedConstructorArgument('rfcUuidString'))
        {
            return UuidV4::fromString(...$this->instantiator->getAssignedConstructorArguments());
        }

        return new UuidV4;
    }
}
