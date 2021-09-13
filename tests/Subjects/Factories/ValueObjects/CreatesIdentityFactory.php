<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesIdentity;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\IdentityFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\IdentityFactoryInterface;

trait CreatesIdentityFactory
{
    use CreatesInstantiator;
    use CreatesIdentity;

    private function createRealIdentityFactory(): IdentityFactoryInterface
    {
        return new IdentityFactory(instantiator: $this->createRealInstantiator());
    }

    private function createFakeIdentityFactory(): IdentityFactoryInterface
    {
        $factory = $this->getMockBuilder(IdentityFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withIdentity')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakeIdentity());

        return $factory;
    }
}
