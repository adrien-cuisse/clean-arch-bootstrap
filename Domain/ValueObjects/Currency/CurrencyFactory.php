<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;

final class CurrencyFactory implements CurrencyFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withName(string $name): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            'name',
            $name
        );

        return $this;
    }

    public function withSymbol(string $symbol): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            'symbol',
            $symbol
        );

        return $this;
    }

    public function build(): CurrencyInterface
    {
        return $this->instantiator->instantiate(Currency::class);
    }
}