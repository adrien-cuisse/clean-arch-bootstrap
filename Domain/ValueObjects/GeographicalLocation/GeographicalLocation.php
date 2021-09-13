<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

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
        $latitudeString = $this->degreesString($this->latitude, minimumDigitsCount: 2, decimalsCount: 4);
        $longitudeString = $this->degreesString($this->longitude, minimumDigitsCount: 3, decimalsCount: 4);

        return "{$latitudeString}{$longitudeString}";
    }

    public function degreesMinutesFormat(): string
    {
        $latitudeString = $this->degreesMinutesString(
            $this->latitude,
            $this->latitudeCardinal(),
            minimumDegreesDigits: 2,
            minutesDecimals: 2
        );
        $longitudeString = $this->degreesMinutesString(
            $this->longitude,
            $this->longitudeCardinal(),
            minimumDegreesDigits: 3,
            minutesDecimals: 2
        );

        return "{$latitudeString}{$longitudeString}";
    }

    public function degreesMinutesSecondsFormat(): string
    {
        $latitudeString = $this->degreesMinutesSecondsString(
            $this->latitude,
            $this->latitudeCardinal(),
            2
        );
        $longitudeString = $this->degreesMinutesSecondsString(
            $this->longitude,
            $this->longitudeCardinal(),
            3
        );

        return "{$latitudeString}{$longitudeString}";
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
     * @param int $minimumDigitsCount - minimum digits to display, left padding with '0' will be done if needed
     * @param int $decimalsCount - how many fixed decimals to display
     *
     * @return string - formated degrees string (eg., [+01.2345], [+123.45])
     */
    private function degreesString(float $coordinate, int $minimumDigitsCount, int $decimalsCount): string
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
     * Formats an angle to a degrees/minutes string with leading zeros and cardinal
     *
     * @param float $angle - the angle to format
     * @param string $cardinal - the cardinal associated to the angle
     * @param int $degreesDigits - minimum digits to display for the degrees, left padding with '0' will be done if needed
     * @param int $minutesDecimals - how many fixed decimals to display for the minutes
     *
     * @return string - formatted degrees/minutes string (eg., [12°23.456'X], [12°123.45'X] where X and Y are cardinals)
     */
    private function degreesMinutesString(float $angle, string $cardinal, int $minimumDegreesDigits, int $minutesDecimals): string
    {
        [$degrees, $minutes] = $this->decomposeAngle($angle);

        $minutesPaddedStringSize = 2 + 1 + $minutesDecimals;

        $minutes = round($minutes, 2);
        if ($minutes == 60) {
            $minutes = 0;
            $degrees++;
        }
        $minutesString = number_format($minutes, decimals: $minutesDecimals);
        $minutesString = str_pad($minutesString, length: $minutesPaddedStringSize, pad_string: '0', pad_type: STR_PAD_LEFT);

        $degreesString = str_pad($degrees, length: $minimumDegreesDigits, pad_string: '0', pad_type: STR_PAD_LEFT);

        return "{$degreesString}°{$minutesString}'{$cardinal}";
    }

    /**
     * Formats an angle to a degrees/minutes/seconds string with leading zeros and cardinals
     *
     * @param float $angle - the angle to format
     * @param string $cardinal - the cardinal associated to the angle
     * @param int $minimumDegreesDigits - how many digits to display for the degrees, left padding with '0' will be done if needed
     *
     * @return string - formated degrees/minutes/seconds string (eg., [00°00'000"X], [12°34'56"Y] where X and Y are cardinals)
     */
    private function degreesMinutesSecondsString(float $angle, string $cardinal, int $minimumDegreesDigits): string
    {
        [$degrees, $decimalMinutes] = $this->decomposeAngle($angle);
        [$minutes, $seconds] = $this->decomposeAngle($decimalMinutes);

        $seconds = round($seconds);
        if ($seconds == 60) {
            $seconds = 0;
            $minutes++;
        }
        $secondsString = str_pad($seconds, length: 2, pad_string: '0', pad_type: STR_PAD_LEFT);

        if ($minutes === 60) {
            $minutes = 0;
            $degrees++;
        }
        $minutesString = str_pad($minutes, length: 2, pad_string: '0', pad_type: STR_PAD_LEFT);

        $degreesString = str_pad($degrees, length: $minimumDegreesDigits, pad_string: '0', pad_type: STR_PAD_LEFT);



        return "{$degreesString}°{$minutesString}'{$secondsString}\"{$cardinal}";
    }

    /**
     * @param float $angle - the decimal angle to convert (ie., from degrees to minutes, from minutes to seconds)
     *
     * @return array<integer,float> - integer units, decimal sub-units
     */
    private function decomposeAngle(float $angle): array
    {
        $angle = abs($angle);
        $units = intval($angle);
        $division = ($angle - $units) * 60;

        return [$units, $division];
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
