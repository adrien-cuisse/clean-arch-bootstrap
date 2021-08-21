<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumber;
use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumberInterface;

final class PhoneNumberFactory extends Factory implements PhoneNumberFactoryInterface
{
    public function withCountryIdentifier(string $countryIdentifier): self
    {
        return $this->assignProperty(
            propertyName: 'countryIdentifier',
            value: $countryIdentifier
        );
    }

    public function withLocalNumber(string $localNumber): PhoneNumberFactoryInterface
    {
        return $this->assignProperty(
            propertyName: 'localNumber',
            value: $localNumber
        );
    }

    public function build(): PhoneNumberInterface
    {
        return $this->genericBuild(class: PhoneNumber::class);
    }
}
