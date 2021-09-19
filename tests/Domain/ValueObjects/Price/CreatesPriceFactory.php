<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Price;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceFactory;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;

trait CreatesPriceFactory
{
    use CreatesInstantiator;

    public function createRealCurrencyFactory(): PriceFactoryInterface
    {
        return new PriceFactory(
            instantiator: $this->createRealInstantiator()
        );
    }
}
