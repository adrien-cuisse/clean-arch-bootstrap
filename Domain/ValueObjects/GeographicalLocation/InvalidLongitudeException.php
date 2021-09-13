<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

use UnexpectedValueException;

final class InvalidLongitudeException extends UnexpectedValueException
{
    public function __construct(private float $longitude)
    {
        parent::__construct(
            "The longitude {$this->longitude} is invalid, it must be between -180 and +180"
        );
    }
}
