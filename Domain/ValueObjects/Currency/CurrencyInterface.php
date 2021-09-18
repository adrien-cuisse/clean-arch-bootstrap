<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency;

use Stringable;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

interface CurrencyInterface extends Stringable, ValueObjectInterface
{
    public function symbol(): string;

    public function name(): string;
}
