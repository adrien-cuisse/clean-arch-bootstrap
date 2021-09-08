<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumber;
use Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumberInterface;

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
