<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use OverflowException;

final class InvalidUuidVersionException extends OverflowException
{
    public function __construct(int $version)
    {
        parent::__construct(
            "Version {$version} is invalid, it may not exceed 15"
        );
    }
}
