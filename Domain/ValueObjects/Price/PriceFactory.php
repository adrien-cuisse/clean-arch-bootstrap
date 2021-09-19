<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;

final class PriceFactory implements PriceFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withAmount(float $amount): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            'amount',
            $amount
        );

        return $this;
    }

    public function withCurrency(CurrencyInterface $currency): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            'currency',
            $currency
        );

        return $this;
    }

    public function build(): PriceInterface
    {
        return $this->instantiator->instantiate(Price::class);
    }
}