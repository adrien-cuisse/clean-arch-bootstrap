<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Instantiator;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;

trait CreatesInstantiator
{
    private function createRealInstantiator(): InstantiatorInterface
    {
        return new Instantiator;
    }

    private function createFakeInstantiator(): InstantiatorInterface
    {
        $instantiator = $this->getMockBuilder(InstantiatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $instantiator->method('createInstance')->willReturn($instantiator);
        $instantiator->method('assignConstructorArgument')->willReturn($instantiator);
        $instantiator->method('getAssignedArguments')->willReturn([]);
        $instantiator->method('hasAssignedArgument')->willReturn(false);

        return $instantiator;
    }
}
