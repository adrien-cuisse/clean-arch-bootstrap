<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocation;

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
