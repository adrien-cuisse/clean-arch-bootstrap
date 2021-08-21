<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\UuidV4;

final class IdentityFactory extends Factory implements IdentityFactoryInterface
{
    public function withIdentity(string $identity): self
    {
        return $this->assignProperty(
            propertyName: 'rfcUuidString',
            value: $identity
        );
    }

    public function build(): IdentityInterface
    {
        if ($this->hasAssignedProperty('rfcUuidString')) {
            $identity = UuidV4::fromString(...$this->getAssignedProperties());
        } else {
            $identity = new UuidV4;
        }

        return $identity;
    }
}
