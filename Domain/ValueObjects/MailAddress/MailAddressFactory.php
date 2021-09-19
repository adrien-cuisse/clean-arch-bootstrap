<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;

final class MailAddressFactory implements MailAddressFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withMailAddress(string $address): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument('mailAddress', $address);

        return $this;
    }

    public function build(): MailAddressInterface
    {
        return $this->instantiator->instantiate(MailAddress::class);
    }
}
