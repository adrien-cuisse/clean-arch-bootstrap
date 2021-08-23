<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

final class EmailFactory implements EmailFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withEmail(string $address): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument(
            name: 'email',
            value: $address
        );

        return $this;
    }

    public function build(): EmailInterface
    {
        return $this->instantiator->createInstance(Email::class);
    }
}
