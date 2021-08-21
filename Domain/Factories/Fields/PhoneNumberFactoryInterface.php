<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumberInterface;

interface PhoneNumberFactoryInterface extends FactoryInterface
{
    public function withCountryIdentifier(string $countryIdentifier): self;

    public function withLocalNumber(string $localNumber): self;

    public function build(): PhoneNumberInterface;
}
