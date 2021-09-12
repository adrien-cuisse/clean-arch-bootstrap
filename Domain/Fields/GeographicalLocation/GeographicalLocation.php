<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation;

final class GeographicalLocation implements GeographicalLocationInterface
{
    public function __construct(private float $latitude, private float $longitude)
    {
        if (($latitude < -90) || ($latitude > +90)) {
            throw new InvalidLatitudeException($latitude);
        }
        if (($longitude < -180) || ($longitude > +180)) {
            throw new InvalidLongitudeException($longitude);
        }
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function degreesFormat(): string
    {
        $latitudeString = $this->formatAngle($this->latitude, minimumDigitsCount: 2, decimalsCount: 4);
        $longitudeString = $this->formatAngle($this->longitude, minimumDigitsCount: 3, decimalsCount: 4);

        return "{$latitudeString}{$longitudeString}";
    }

    public function degreesMinutesFormat(): string
    {
        $latitudeString = $this->formatAngleMinutes(
            $this->latitude,
            $this->latitudeCardinal(),
            minimumDegreesDigits: 2,
            minutesDecimals: 2
        );
        $longitudeString = $this->formatAngleMinutes(
            $this->longitude,
            $this->longitudeCardinal(),
            minimumDegreesDigits: 3,
            minutesDecimals: 2
        );

        return "{$latitudeString}{$longitudeString}";
    }

    public function degreesMinutesSecondsFormat(): string
    {
        [$latitudeDegrees, $latitudeMinutes, $latitudeSeconds] = $this->degreesMinutesSeconds($this->latitude);
        $latitudeCardinal = $this->latitudeCardinal();

        [$longitudeDegrees, $longitudeMinutes, $longitudeSeconds] = $this->degreesMinutesSeconds($this->longitude);
        $longitudeCardinal = $this->longitudeCardinal();

        return "{$latitudeDegrees}° {$latitudeMinutes}' {$latitudeSeconds}\" {$latitudeCardinal} {$longitudeDegrees}° {$longitudeMinutes}' {$longitudeSeconds}\" {$longitudeCardinal}";
    }

    /**
     * @see Stringable
     */
    public function __toString()
    {
        return $this->degreesMinutesSecondsFormat();
    }

    /**
     * Formats an angle to a string with leading sign and leading zeros
     *
     * @param float $coordinate - the angle to format
     * @param int $minimumDigitsCount - minimum digits to display, padding with '0' will be done if needed
     * @param int $decimalsCount - how many fixed decimals to display
     *
     * @return string - formated angle string (eg., '+01.2345', '+123.45')
     */
    private function formatAngle(float $coordinate, int $minimumDigitsCount, int $decimalsCount): string
    {
        $leadingSign = '+';
        if ($coordinate < 0) {
            $leadingSign = '-';
        }

        $coordinate = abs($coordinate);

        $numericStringSize = $minimumDigitsCount + 1 + $decimalsCount;

        $coordinateString = number_format($coordinate, decimals: $decimalsCount);
        $coordinateString = str_pad($coordinateString, length: $numericStringSize, pad_string: '0', pad_type: STR_PAD_LEFT);

        return "{$leadingSign}{$coordinateString}";
    }

    /**
     * Formats an angle to a degree/minutes string with leading zeros and cardinal
     *
     * @param float $angle - the angle to format
     * @param string $cardinal - the associated cardinal of the angle
     * @param int $degreesDigits - minimum digits to display for the degrees, padding with '0' will be done if needed
     * @param int $minutesDecimals - how many fixed decimals to display for the minutes
     *
     * @return string - formatted angle/minute string (eg., "12°23.45'X123°45.67'Y" where X and Y are cardinals)
     */
    private function formatAngleMinutes(float $angle, string $cardinal, int $minimumDegreesDigits, int $minutesDecimals): string
    {
        [$degrees, $minutes] = $this->degreesMinutes($angle);

        $degreesString = str_pad($degrees, length: $minimumDegreesDigits, pad_string: '0', pad_type: STR_PAD_LEFT);

        $minutesPaddedStringSize = 2 + 1 + $minutesDecimals;
        $minutesString = number_format($minutes, decimals: $minutesDecimals);
        $minutesString = str_pad($minutesString, length: $minutesPaddedStringSize, pad_string: '0', pad_type: STR_PAD_LEFT);

        return "{$degreesString}°{$minutesString}'{$cardinal}";
    }

    /**
     * @param float $angle - the decimal angle to convert
     *
     * @return array<integer,float> - integer degrees, decimal seconds with 6 digits
     */
    private function degreesMinutes(float $angle): array
    {
        $angle = abs($angle);
        $degrees = intval($angle);
        $minutes = ($angle - $degrees) * 60;

        return [$degrees, $minutes];
    }

    /**
     * @param float $coordinate - the decimal angle to convert
     *
     * @return array<int,int,float> - integer degrees, integer minutes, decimal seconds with 3 digits
     */
    private function degreesMinutesSeconds(float $coordinate): array
    {
        [$degrees, $decimalMinutes] = $this->degreesMinutes($coordinate);
        [$minutes, $seconds] = $this->degreesMinutes($decimalMinutes);

        return [$degrees, $minutes, $seconds];
    }

    private function latitudeCardinal(): string
    {
        if ($this->latitude >= 0) {
            return 'N';
        }

        return 'S';
    }

    private function longitudeCardinal(): string
    {
        if ($this->longitude >= 0) {
            return 'E';
        }

        return 'W';
    }
}
