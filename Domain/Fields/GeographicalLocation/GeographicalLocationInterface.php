<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation;

use Stringable;

/**
 * Some geographical location, made of a latitude and a longitude
 *
 * @link https://www.w3.org/2005/Incubator/geo/Wiki/LatitudeLongitudeAltitude
 */
interface GeographicalLocationInterface extends Stringable
{
    public function latitude(): float;

    public function longitude(): float;

    /**
     * Formats the location to ISO 6709 DD format
     * Leading signs are mandatory, leading zeros are mandatory, decimals padding is not
     *
     * @return string - ISO 6709 DD format-string (eg., '+00.0000+000.0000', '-12.3456-123.4567')
     */
    public function degreesFormat(): string;

    /**
     * @return string - integer angle and decimal minutes with cardinal (eg., [7° 3.14' N 11° 0.618 W])
     */
    public function degreesMinutesFormat(): string;

    /**
     * @return string - integer angle, integer mintues and decimal seconds with cardinal (eg., [7° 3' 0.618" S 11° 8 3.14" E])
     */
    public function degreesMinutesSecondsFormat(): string;
}
