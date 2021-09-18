<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

final class Currency implements CurrencyInterface
{
    public function __construct(private string $name, private string $symbol)
    {
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @see Stringable
     */
    public function __toString(): string
    {
        return $this->symbol;
    }

    /**
     * @see ValueObjectInterface
     */
    public function equals(ValueObjectInterface $other): bool
    {
        if ($other instanceof CurrencyInterface) {
            return ($this->name() === $other->name()) && ($this->symbol() === $other->symbol());
        }

        return false;
    }
}
