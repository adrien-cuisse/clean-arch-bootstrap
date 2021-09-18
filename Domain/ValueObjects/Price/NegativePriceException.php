<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

use UnexpectedValueException;

final class NegativePriceException extends UnexpectedValueException
{
    public function __construct(private float $amount)
    {
        parent::__construct(
            "The price can't be ne gative, got '{$this->amount}'"
        );
    }
}
