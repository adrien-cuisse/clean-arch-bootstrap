<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

interface CurrencyInterface extends ValueObjectInterface
{
    public function symbol(): string;

    public function name(): string;
}
