<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocation;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationInterface;

trait CreatesGeographicalLocation
{
    private function createRealGeographicalLocation(float $latitude = 0, float $longitude = 0): GeographicalLocationInterface
    {
        return new GeographicalLocation(
            latitude: $latitude,
            longitude: $longitude
        );
    }
}
