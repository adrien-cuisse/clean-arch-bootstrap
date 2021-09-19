<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\InstantiatorInterface;

final class GeographicalLocationFactory implements GeographicalLocationFactoryInterface
{
    public function __construct(private InstantiatorInterface $instantiator)
    {
    }

    public function withLatitude(float $latitude): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument('latitude', $latitude);

        return $this;
    }

    public function withLongitude(float $longitude): self
    {
        $this->instantiator = $this->instantiator->assignConstructorArgument('longitude', $longitude);

        return $this;
    }

    public function build(): GeographicalLocationInterface
    {
        return $this->instantiator->instantiate(GeographicalLocation::class);
    }
}
