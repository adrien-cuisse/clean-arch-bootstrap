<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\Currency;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;

trait CreatesCurrency
{
    private function createRealCurrency(string $name = '', string $symbol = ''): CurrencyInterface
    {
        return new Currency(
            name: $name,
            symbol: $symbol
        );
    }
}

