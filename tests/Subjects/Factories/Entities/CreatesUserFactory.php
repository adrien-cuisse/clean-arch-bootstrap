<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Entities;

use Alphonse\CleanArchBootstrap\Domain\Factories\Entities\UserFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Entities\UserFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Entities\CreatesUser;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesEmailFactory;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesIdentityFactory;

trait CreatesUserFactory
{
    use CreatesIdentityFactory;
    use CreatesEmailFactory;
    use CreatesUser;

    private function createRealUserFactory(): UserFactoryInterface
    {
        return new UserFactory(
            identityFactory: $this->createRealIdentityFactory(),
            emailFactory: $this->createRealEmailFactory(),
        );
    }

    private function createFakeEmailFactory(): UserFactoryInterface
    {
        $factory = $this->getMockBuilder(UserFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withIdentity')->willReturn($factory);
        $factory->method('withEmail')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakeUser());

        return $factory;
    }
}
