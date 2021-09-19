<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface MailAddressFactoryInterface extends FactoryInterface
{
    public function withMailAddress(string $address): self;

    public function build(): MailAddressInterface;
}
