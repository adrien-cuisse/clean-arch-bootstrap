<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface PhoneNumberFactoryInterface extends FactoryInterface
{
    public function withCountryIdentifier(string $countryIdentifier): self;

    public function withLocalNumber(string $localNumber): self;

    public function build(): PhoneNumberInterface;
}
