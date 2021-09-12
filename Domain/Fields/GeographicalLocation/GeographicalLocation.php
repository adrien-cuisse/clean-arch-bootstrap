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
        return "{$this->latitude}, {$this->longitude}";
    }

    public function degreesMinutesFormat(): string
    {
        [$latitudeDegrees, $latitudeMinutes] = $this->degreesMinutes($this->latitude);
        $latitudeCardinal = $this->latitudeCardinal();

        [$longitudeDegrees, $longitudeMinutes] = $this->degreesMinutes($this->longitude);
        $longitudeCardinal = $this->longitudeCardinal();

        return "{$latitudeDegrees}° {$latitudeMinutes}' {$latitudeCardinal} {$longitudeDegrees}° {$longitudeMinutes}' {$longitudeCardinal}";
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
     * @param float $angle - the decimal angle to convert
     *
     * @return array<integer,float> - integer degrees, decimal seconds with 6 digits
     */
    private function degreesMinutes(float $angle): array
    {
        $totalMinutes = abs($angle) * 60;

        $degrees = intval($totalMinutes / 60);
        $minutes = $totalMinutes - ($degrees * 60);

        $minutes = round($minutes, 6);

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
