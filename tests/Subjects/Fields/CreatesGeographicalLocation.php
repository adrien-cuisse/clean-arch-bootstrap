<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocationInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocation;

trait CreatesGeographicalLocation
{
    private function createRealGeographicalLocation(float $latitude = 0, float $longitude = 0): GeographicalLocationInterface
    {
        return new GeographicalLocation($latitude, $longitude);
    }
}
