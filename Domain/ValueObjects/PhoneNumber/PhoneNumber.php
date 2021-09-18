<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

final class PhoneNumber implements PhoneNumberInterface
{
    public function __construct(private string $countryIdentifier, private string $localNumber)
    {
    }

    public function nationalFormat(): string
    {
        return "{$this->localNumber}";
    }

    public function internationalFormat(): string
    {
        return "+{$this->countryIdentifier}{$this->localNumber}";
    }

    /**
     * @see Stringable
     */
    public function __toString(): string
    {
        return $this->internationalFormat();
    }

    /**
     * @see ValueObjectInterface
     */
    public function equals(ValueObjectInterface $other): bool
    {
        if ($other instanceof PhoneNumberInterface) {
            return $this->internationalFormat() === $other->internationalFormat();
        }

        return false;
    }
}
