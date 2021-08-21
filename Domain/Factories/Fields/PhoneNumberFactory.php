<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumber;
use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumberInterface;

final class PhoneNumberFactory implements PhoneNumberFactoryInterface
{
    private Iterable $properties = [];

    public function withCountryIdentifier(string $countryIdentifier): self
    {
        $factory = new self;
        $factory->properties['countryIdentifier'] = $countryIdentifier;

        return $factory;
    }

    public function withLocalNumber(string $localNumber): PhoneNumberFactoryInterface
    {
        $factory = new self;
        $factory->properties['localNumber'] = $localNumber;

        return $factory;
    }

    public function build(): PhoneNumberInterface
    {
        return new PhoneNumber(...$this->properties);
    }
}
