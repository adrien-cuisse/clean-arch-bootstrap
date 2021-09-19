<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Identity;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\IdentityFactory;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\IdentityFactoryInterface;

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
