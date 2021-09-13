<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

final class MailAddress implements MailAddressInterface
{
    public function __construct(private string $mailAddress)
    {
        if (filter_var($this->mailAddress, FILTER_VALIDATE_EMAIL) === false) {
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

    /**
     * @see ValueObjectInterface
     */
    public function equals(ValueObjectInterface $other): bool
    {
        if ($other instanceof MailAddressInterface) {
            return (string) $this === (string) $other;
        }

        return false;
    }
}
