<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

use LengthException;

final class InvalidUuidTimestampMidBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected timestamp-mid to contain 2 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
