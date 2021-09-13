<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\MailAddressFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\MailAddressFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesMailAddress;

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
