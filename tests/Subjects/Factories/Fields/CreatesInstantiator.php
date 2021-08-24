<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\Instantiator;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\InstantiatorInterface;

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
