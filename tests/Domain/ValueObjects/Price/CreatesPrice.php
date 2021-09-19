<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Price;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\Price;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Currency\CreatesCurrency;

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

