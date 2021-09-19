<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects;

use BadMethodCallException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

trait CreatesDummyValueObject
{
    private function createDummyValueObject(): ValueObjectInterface
    {
        return new class implements ValueObjectInterface {
            public function equals(ValueObjectInterface $other): bool
            {
                throw new BadMethodCallException("Method must be called from concrete instance");
            }
        };
    }
}
