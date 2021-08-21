<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

final class EmailFactory extends Factory implements EmailFactoryInterface
{
    public function withEmail(string $address): self
    {
        return $this->assignProperty(
            propertyName: 'email',
            value: $address
        );
    }

    public function build(): EmailInterface
    {
        return $this->genericBuild(Email::class);
    }
}
