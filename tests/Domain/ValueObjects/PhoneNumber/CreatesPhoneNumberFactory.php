<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\PhoneNumber;

use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberFactory;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberFactoryInterface;

trait CreatesPhoneNumberFactory
{
    use CreatesInstantiator;
    use CreatesPhoneNumber;

    private function createRealPhoneNumberFactory(): PhoneNumberFactoryInterface
    {
        return new PhoneNumberFactory(instantiator: $this->createRealInstantiator());
    }

    private function createFakePhoneNumberFactory(): PhoneNumberFactoryInterface
    {
        $factory = $this->getMockBuilder(PhoneNumberFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withCountryIdentifier')->willReturn($factory);
        $factory->method('withLocalNumber')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakePhoneNumber());

        return $factory;
    }
}
