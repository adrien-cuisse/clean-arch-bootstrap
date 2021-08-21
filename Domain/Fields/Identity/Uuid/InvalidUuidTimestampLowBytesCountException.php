<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid;

use LengthException;

final class InvalidUuidTimestampLowBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected timestamp-low to contain 4 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
