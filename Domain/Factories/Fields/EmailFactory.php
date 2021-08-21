<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

final class EmailFactory implements EmailFactoryInterface
{
    private Iterable $properties = [];

    public function withEmail(string $address): self
    {
        $factory = new self;
        $factory->properties['email'] = $address;

        return $factory;
    }

    public function build(): EmailInterface
    {
        return new Email(...$this->properties);
    }
}
