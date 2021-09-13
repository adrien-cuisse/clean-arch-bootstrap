<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

use UnexpectedValueException;

final class InvalidLatitudeException extends UnexpectedValueException
{
    public function __construct(private float $latitude)
    {
        parent::__construct(
            "The latidude {$this->latitude} is invalid, it must be between -90 and +90"
        );
    }
}
