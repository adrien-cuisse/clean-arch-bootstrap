<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Stringable;

/**
 * Some unique identity
 */
interface IdentityInterface extends Stringable, ValueObjectInterface
{
    /**
     * @return int|string - the representation of the identity, in its native type
     */
    public function nativeFormat(): int|string;
}
