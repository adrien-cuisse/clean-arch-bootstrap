<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumber;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberInterface;

final class PhoneNumberFactory implements PhoneNumberFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withCountryIdentifier(string $countryIdentifier): self
    {
        $this->instantiator =  $this->instantiator->assignConstructorArgument('countryIdentifier', $countryIdentifier);

        return $this;
    }

    public function withLocalNumber(string $localNumber): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument('localNumber', $localNumber);

        return $this;
    }

    public function build(): PhoneNumberInterface
    {
        return $this->instantiator->instantiate(PhoneNumber::class);
    }
}
