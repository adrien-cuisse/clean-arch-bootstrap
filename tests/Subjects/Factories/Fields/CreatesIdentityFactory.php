<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesIdentity;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\IdentityFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\IdentityFactoryInterface;

trait CreatesIdentityFactory
{
    use CreatesIdentity;

    private function createRealIdentityFactory(): IdentityFactoryInterface
    {
        return new IdentityFactory;
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
