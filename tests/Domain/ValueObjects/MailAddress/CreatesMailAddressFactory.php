<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressFactory;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressFactoryInterface;

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
