<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use LengthException;

final class InvalidUuidTimeHighBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected time-high to contain 2 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
