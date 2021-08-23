<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\MailAddressFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\MailAddressFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesMailAddress;

trait CreatesMailAddressFactory
{
    use CreatesInstantiator;
    use CreatesMailAddress;

    private function createRealMailAddressFactory(): MailAddressFactoryInterface
    {
        return new MailAddressFactory(instantiator: $this->createRealInstantiator());
    }

    private function createFakeMailAddressFactory(): MailAddressFactoryInterface
    {
        $factory = $this->getMockBuilder(MailAddressFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withMailAddress')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakeMailAddress());

        return $factory;
    }
}
