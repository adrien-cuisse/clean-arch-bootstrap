<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesPhoneNumber;

trait CreatesPhoneNumberFactory
{
    use CreatesPhoneNumber;

    private function createRealPhoneNumberFactory(): PhoneNumberFactoryInterface
    {
        return new PhoneNumberFactory;
    }

    private function createFakePhoneNumberFactory(): PhoneNumberFactoryInterface
    {
        $factory = $this->getMockBuilder(PhoneNumberFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        // $factory->method('withEmail')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakePhoneNumber());

        return $factory;
    }
}
