<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use BadMethodCallException;

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
