<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocation;
use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocationInterface;

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
