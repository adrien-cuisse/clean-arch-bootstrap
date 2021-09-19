<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface CurrencyFactoryInterface extends FactoryInterface
{
    public function build(): CurrencyInterface;

    public function withName(string $name): self;

    public function withSymbol(string $symbol): self;
}
