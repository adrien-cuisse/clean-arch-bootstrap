<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface PriceFactoryInterface extends FactoryInterface
{
    public function build(): PriceInterface;

    public function withAmount(float $amount): self;

    public function withCurrency(CurrencyInterface $currency): self;
}
