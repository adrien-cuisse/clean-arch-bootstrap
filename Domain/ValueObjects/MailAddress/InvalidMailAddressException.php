<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use UnexpectedValueException;

final class InvalidMailAddressException extends UnexpectedValueException
{
    public function __construct(private string $mailAddress)
    {
        parent::__construct(
            "The mail address '{$this->mailAddress}' is invalid"
        );
    }
}
