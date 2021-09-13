<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressInterface;

interface MailAddressFactoryInterface extends FactoryInterface
{
    public function withMailAddress(string $address): self;

    public function build(): MailAddressInterface;
}
