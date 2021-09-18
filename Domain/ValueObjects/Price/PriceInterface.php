<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

use Stringable;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;

interface PriceInterface extends Stringable, ValueObjectInterface
{
    public function amount(): float;

    public function currency(): CurrencyInterface;
}
