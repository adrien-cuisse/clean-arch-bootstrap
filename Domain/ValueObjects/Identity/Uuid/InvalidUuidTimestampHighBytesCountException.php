<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

use LengthException;

final class InvalidUuidTimestampHighBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected timestemp-high to contain 2 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
