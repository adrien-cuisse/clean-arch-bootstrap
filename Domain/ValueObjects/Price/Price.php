<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

use Stringable;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;

final class Price implements PriceInterface
{
    public function __construct(private float $amount, private CurrencyInterface $currency)
    {
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     * @see Stringable
     */
    public function __toString(): string
    {
        $amountString = number_format($this->amount, decimals: 2);

        return "{$amountString}{$this->currency()->symbol()}";
    }

    public function equals(ValueObjectInterface $other): bool
    {
        if ($other instanceof PriceInterface) {
            return ($this->amount() === $other->amount()) && ($this->currency()->equals($other->currency()));
        }

        return false;
    }
}
