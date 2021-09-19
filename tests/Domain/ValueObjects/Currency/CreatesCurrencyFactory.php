<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Currency;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyFactory;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;

trait CreatesCurrencyFactory
{
    use CreatesInstantiator;

    public function createRealCurrencyFactory(): CurrencyFactoryInterface
    {
        return new CurrencyFactory(
            instantiator: $this->createRealInstantiator()
        );
    }
}
