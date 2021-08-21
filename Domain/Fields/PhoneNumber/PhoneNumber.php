<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber;

final class PhoneNumber implements PhoneNumberInterface
{
    public function __construct(private string $countryIdentifier, private string $localNumber)
    {
    }

    public function toNationalFormat(): string
    {
        return "{$this->localNumber}";
    }

    public function toInternationalFormat(): string
    {
        return "+{$this->countryIdentifier}{$this->localNumber}";
    }

    public function __toString(): string
    {
        return $this->toInternationalFormat();
    }
}
