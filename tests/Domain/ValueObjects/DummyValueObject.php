<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use BadMethodCallException;

final class DummyValueObject implements ValueObjectInterface
{
    public function equals(ValueObjectInterface $other): bool
    {
        throw new BadMethodCallException("Method must be called from concrete instance");
    }
}
