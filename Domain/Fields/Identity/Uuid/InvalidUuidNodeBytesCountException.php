<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid;

use LengthException;

final class InvalidUuidNodeBytesCountException extends LengthException
{
    public function __construct(array $bytes)
    {
        parent::__construct(sprintf(
            "Expected node to contain 6 bytes, found [%s]",
            implode(', ', $bytes)
        ));
    }
}
