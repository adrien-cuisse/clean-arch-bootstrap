<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\Price;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;

trait CreatesPrice
{
    use CreatesCurrency;

    private function createRealPrice(float $amount = 0, ?CurrencyInterface $currency = null): PriceInterface
    {
        if ($currency === null) {
            $currency = $this->createRealCurrency(name: 'euro', symbol: '€');
        }

        return new Price(
            amount: $amount,
            currency: $currency
        );
    }
}

