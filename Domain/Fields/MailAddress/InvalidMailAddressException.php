<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress;

use UnexpectedValueException;

final class InvalidMailAddressException extends UnexpectedValueException
{
    public function __construct(private string $mailAddress)
    {
        parent::__construct(
            message: "The mail address '{$this->mailAddress}' is invalid"
        );
    }
}
