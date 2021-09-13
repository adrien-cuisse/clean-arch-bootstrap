<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberInterface;

interface PhoneNumberFactoryInterface extends FactoryInterface
{
    public function withCountryIdentifier(string $countryIdentifier): self;

    public function withLocalNumber(string $localNumber): self;

    public function build(): PhoneNumberInterface;
}
