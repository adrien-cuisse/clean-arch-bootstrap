<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress;

final class MailAddress implements MailAddressInterface
{
    public function __construct(private string $mailAddress)
    {
        if (filter_var(value: $this->mailAddress, filter: FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidMailAddressException($this->mailAddress);
        }
    }

    /**
     * @see Stringable
     */
    public function __toString(): string
    {
        return $this->mailAddress;
    }
}
