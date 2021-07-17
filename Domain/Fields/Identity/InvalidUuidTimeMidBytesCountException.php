<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity;

use LengthException;

final class InvalidUuidTimeMidBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected time-mid to contain 2 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}