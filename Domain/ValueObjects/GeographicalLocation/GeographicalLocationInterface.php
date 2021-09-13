<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

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
     * Formats the location to ISO 6709:2009 DD format
     * Leading signs are mandatory, leading zeros are mandatory, decimals padding is optional
     *
     * @return string - ISO 6709 DD format-string (eg., [+00.0000+000.0000], [-12.3456-123.4567])
     */
    public function degreesFormat(): string;

    /**
     * Formats the location to recommended ISO 6709:2009 DDM format
     * Leading zeros are mandatory, cardinals are mantory, decimal padding is optional
     *
     * @return string - ISO 6709:2009 DDM format-string (eg., [00°00.00'N000°00.00'E], [12°23.45'S123°45.67'W])
     */
    public function degreesMinutesFormat(): string;

    /**
     * Formats the location to recommended ISO 6709:2009 DMS format
     * Leading zeros are mandatory, cardinals are mandatory
     *
     * @return string - ISO 6709:2009 DMS format-string (eg., [00°00'00"N000°00'00"E], [12°34'56"S123°45'67"W])
     */
    public function degreesMinutesSecondsFormat(): string;
}
