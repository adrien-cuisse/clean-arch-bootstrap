<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use LengthException;

final class InvalidUuidTimeLowBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected time-low to contain 4 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
