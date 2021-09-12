<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesGeographicalLocation;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\GeographicalLocationFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\GeographicalLocationFactoryInterface;

trait CreatesGeographicalLocationFactory
{
    use CreatesInstantiator;
    use CreatesGeographicalLocation;

    private function createRealGeographicalLocationFactory(): GeographicalLocationFactoryInterface
    {
        return new GeographicalLocationFactory(instantiator: $this->createRealInstantiator());
    }

    private function createFakeGeographicalLocationFactory(): GeographicalLocationFactoryInterface
    {
        $factory = $this->getMockBuilder(GeographicalLocationFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withLatitude')->willReturn($factory);
        $factory->method('withLongitude')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakeGeographicalLocation());

        return $factory;
    }
}
