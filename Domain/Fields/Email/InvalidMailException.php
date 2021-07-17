<?php

namespace Alphonse\CleanArch\Domain\Fields\Email;

use UnexpectedValueException;

final class InvalidMailException extends UnexpectedValueException
{
    public function __construct(private string $email)
    {
        parent::__construct(
            message: "The mail address '{$this->email}' is invalid"
        );
    }
}
