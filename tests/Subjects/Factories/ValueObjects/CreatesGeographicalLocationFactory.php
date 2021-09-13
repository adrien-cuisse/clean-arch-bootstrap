<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesGeographicalLocation;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\GeographicalLocationFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\GeographicalLocationFactoryInterface;

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
