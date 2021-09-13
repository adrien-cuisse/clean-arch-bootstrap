<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects;

interface ValueObjectInterface
{
    /**
     * @param ValueObjectInterface $other - the instance to compare with
     *
     * @return bool - true if the $other value object represent the same thing (ie., implements the same interface)
     *  and has same characteristic properties, false otherwise
     */
    public function equals(ValueObjectInterface $other): bool;
}
