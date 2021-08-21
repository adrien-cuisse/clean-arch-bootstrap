<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

interface EmailFactoryInterface
{
    public function withEmail(string $address): self;

    public function build(): EmailInterface;
}
