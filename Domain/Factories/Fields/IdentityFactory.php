<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\UuidV4;

final class IdentityFactory implements IdentityFactoryInterface
{
    private Iterable $properties = [];

    public function withIdentity(string $identity): self
    {
        $factory = new self;
        $factory->properties['rfcUuidString'] = $identity;

        return $factory;
    }

    public function build(): IdentityInterface
    {
        if (isset($this->properties['rfcUuidString'])) {
            $identity = UuidV4::fromString(...$this->properties);
        } else {
            $identity = new UuidV4;
        }

        return $identity;
    }
}
