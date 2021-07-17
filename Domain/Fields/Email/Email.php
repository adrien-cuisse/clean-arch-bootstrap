<?php

namespace Alphonse\CleanArch\Domain\Fields\Email;

final class Email implements EmailInterface
{
    public function __construct(private string $email)
    {
        if (filter_var(value: $this->email, filter: FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidMailException($this->email);
        }
    }

    /**
     * @see Stringable
     */
    public function __toString(): string
    {
        return $this->email;
    }
}
