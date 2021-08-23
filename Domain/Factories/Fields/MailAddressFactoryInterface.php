<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

interface MailAddressFactoryInterface extends FactoryInterface
{
    public function withMailAddress(string $address): self;

    public function build(): MailAddressInterface;
}
