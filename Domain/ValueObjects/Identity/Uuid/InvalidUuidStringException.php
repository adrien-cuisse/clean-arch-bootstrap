<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

use InvalidArgumentException;

final class InvalidUuidStringException extends InvalidArgumentException
{
    public function __construct(string $uuidString)
    {
        parent::__construct(
            "The Uuid {$uuidString} is not compliant with the RFC format"
        );
    }
}
