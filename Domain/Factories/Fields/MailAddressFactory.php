<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddress;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

final class MailAddressFactory implements MailAddressFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withMailAddress(string $address): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            name: 'mailAddress',
            value: $address
        );

        return $this;
    }

    public function build(): MailAddressInterface
    {
        return $this->instantiator->createInstance(MailAddress::class);
    }
}
