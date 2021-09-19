<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesInstantiator;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationFactory;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationFactoryInterface;

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
