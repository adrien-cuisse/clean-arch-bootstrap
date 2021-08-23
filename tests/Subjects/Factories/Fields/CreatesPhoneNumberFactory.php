<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesPhoneNumber;

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
