<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\GeographicalLocation;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesDummyValueObject;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLatitudeException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLongitudeException;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationInterface;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocation
 */
final class GeographicalLocationTest extends TestCase
{
    use CreatesDummyValueObject;
    use CreatesGeographicalLocation;

    public function valueObjectProvider(): Generator
    {
        $latitude = 3.14;
        $longitude = 0.816;
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        yield 'not a location' => [
            $location,
            $this->createDummyValueObject(),
            false
        ];
        yield 'location with different latitude' => [
            $location,
            $this->createRealGeographicalLocation(latitude: $latitude + 1, longitude: $longitude),
            false
        ];
        yield 'location with different longitude' => [
            $location,
            $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude + 1),
            false
        ];
        yield 'location with same coordinates' => [
            $location,
            $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_coordinates(GeographicalLocationInterface $location, ValueObjectInterface $other, bool $expectedEquality): void
    {
        // given a value object to compare with

        // when comparing the 2 instances
        $areSameValue = $location->equals($other);

        // when it should match the expected equality
        $this->assertSame(
            expected: $expectedEquality,
            actual: $areSameValue,
        );
    }

    public function invalidCoordinatesProvider(): Generator
    {
        yield 'latitude below minimum' => [-90.1, 0, InvalidLatitudeException::class];
        yield 'latitude above maximum' => [+90.1, 0, InvalidLatitudeException::class];
        yield 'longitude below minimum' => [0, -180.1, InvalidLongitudeException::class];
        yield 'longitude above maximum' => [0, +180.1, InvalidLongitudeException::class];
    }

    /**
     * @test
     * @dataProvider invalidCoordinatesProvider
     */
    public function rejects_invalid_coordinates(float $latitude, float $longitude, string $expectedException): void
    {
        $this->expectException($expectedException);

        // given an invalid coordinate

        // when trying to create a location from it
        $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // then it should be rejected
    }

    public function validCoordinatesProvider(): Generator
    {
        yield 'origin' => [0, 0];
        yield 'max coords' => [+90, +180];
        yield 'min coords' => [-90, -180];
    }

    /**
     * @test
     * @dataProvider validCoordinatesProvider
     */
    public function degrees_format_contains_latitude(float $latitude, float $_): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude);

        // when checking the latitude/longitude format
        $format = $location->degreesFormat();

        // then it should contain the given latitude
        $this->assertStringContainsString(
            needle: $latitude,
            haystack: $format,
            message: "Location ISO DD format ({$format}) doesn't contain the right latitude '{$latitude}'"
        );
    }

    /**
     * @test
     * @dataProvider validCoordinatesProvider
     */
    public function degrees_format_contains_longitude(float $_, float $longitude): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(longitude: $longitude);

        // when checking the latitude/longitude format
        $format = $location->degreesFormat();

        // then it should contain the given longitude
        $this->assertStringContainsString(
            needle: $longitude,
            haystack: $format,
            message: "Location ISO DD format ({$format}) doesn't contain the right longitude '{$longitude}'"
        );
    }

    /**
     * @test
     * @dataProvider validCoordinatesProvider
     *
     * @depends degrees_format_contains_latitude
     * @depends degrees_format_contains_longitude
     */
    public function degrees_format_contains_latitude_first(float $latitude, float $longitude): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(longitude: $longitude);

        // when checking the latitude/longitude format
        $format = $location->degreesFormat();

        // and extracting the position of coordinates in the string
        $latitudeDegreesPosition = strpos(haystack: $format, needle: $latitude);
        $longitudeDegreesPosition = strrpos(haystack: $format, needle: $longitude);

        // then latitude degrees should come first
        $this->assertLessThan(
            expected: $longitudeDegreesPosition,
            actual: $latitudeDegreesPosition,
            message: "Location ISO DD format ({$format}) doesn't contain the latitude '{$latitude}' first"
        );
    }

    public function degreesFormatProvider(): Generator
    {
        yield 'zeros padding and leading plus sign' => [0, 0, '+00.0000+000.0000'];
        yield 'positive DD cords' => [+1, +1, '+01.0000+001.0000'];
        yield 'negative DD coords' => [-1, -1, '-01.0000-001.0000'];
        yield '4 decimals rounding' => [0.12345, 0.56789, '+00.1235+000.5679'];
        yield '4 decimals rounding overflow' => [0.99999, 0.99999, '+01.0000+001.0000'];
    }

    /**
     * @test
     * @dataProvider degreesFormatProvider
     */
    public function has_correct_ISO_DD_format(float $latitude, float $longitude, string $expectedFormat): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its decimal degrees format
        $format = $location->degreesFormat();

        // then it should match the expectation
        $this->assertSame(
            expected: $expectedFormat,
            actual: $format,
            message: "Expected ISO DD format ({$expectedFormat}), got ({$format})"
        );
    }

    public function integerDegreesProvider(): Generator
    {
        yield 'positive coords integer degrees' => [5.0, 10.0, 5, 10];
        yield 'negative coords integer degrees' => [-5.0, -10.0, 5, 10];
        yield 'decimal coords integer coords' => [42.5, 13.175, 42, 13];
    }

    /**
     * @test
     * @dataProvider integerDegreesProvider
     */
    public function degrees_minutes_format_contains_integer_degrees(float $latitude, float $longitude, int $expectedLatitudeDegrees, int $expectedLongitudeDegrees): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format
        $format = $location->degreesMinutesFormat();

        // then it should contain integer degrees
        $this->assertStringContainsString(
            needle: "{$expectedLatitudeDegrees}??",
            haystack: $format,
            message: "Expected ISO DDM format ({$format}) to contain integer latitude degrees"
        );
        $this->assertStringContainsString(
            needle: "{$expectedLongitudeDegrees}??",
            haystack: $format,
            message: "Expected ISO DDM format ({$format}) to contain integer longitude degrees"
        );
    }

    /**
     * @test
     * @dataProvider integerDegreesProvider
     *
     * @depends degrees_minutes_format_contains_integer_degrees
     */
    public function degrees_minutes_format_contains_latitude_degrees_first(float $latitude, float $longitude, int $expectedLatitudeDegrees, int $expectedLongitudeDegrees): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format and extracting the degrees position
        $format = $location->degreesMinutesFormat();
        $latitudeDegreesPosition = strpos(haystack: $format, needle: $expectedLatitudeDegrees);
        $longitudeDegreesPosition = strpos(haystack: $format, needle: $expectedLongitudeDegrees);

        // then latitude degrees should come first
        $this->assertLessThan(
            expected: $longitudeDegreesPosition,
            actual: $latitudeDegreesPosition,
            message: "Location ISO DDM format ({$format}) doesn't contain the latitude degrees '{$expectedLatitudeDegrees}' first"
        );
    }

    public function decimalMinutesProvider(): Generator
    {
        yield 'positive coords decimal minutes' => [13.5, 10.25, '30.00', '15.00'];
        yield 'negative coords decimal minutes' => [-13.51, -10.252, '30.60', '15.12'];
    }

    /**
     * @test
     * @dataProvider decimalMinutesProvider
     */
    public function degrees_minutes_format_contains_decimal_minutes(float $latitude, float $longitude, string $expectedLatitudeMinutes, string $expectedLongitudeMinutes): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format
        $format = $location->degreesMinutesFormat();

        // then it should contain decimal minutes
        $this->assertStringContainsString(
            needle: "{$expectedLatitudeMinutes}'",
            haystack: $format,
            message: "Expected ISO DDM format ({$format}) to contain integer latitude degrees"
        );
        $this->assertStringContainsString(
            needle: "{$expectedLongitudeMinutes}'",
            haystack: $format,
            message: "Expected ISO DDM format ({$format}) to contain integer longitude degrees"
        );
    }

    /**
     * @test
     * @dataProvider decimalMinutesProvider
     *
     * @depends degrees_minutes_format_contains_decimal_minutes
     */
    public function degrees_minutes_format_contains_latitude_minutes_first(float $latitude, float $longitude, string $expectedLatitudeMinutes, string $expectedLongitudeMinutes): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format and extracting the minutes position
        $format = $location->degreesMinutesFormat();
        $latitudeMinutesPosition = strpos(haystack: $format, needle: $expectedLatitudeMinutes);
        $longitudeMinutesPosition = strpos(haystack: $format, needle: $expectedLongitudeMinutes);

        // then latitude minutes should come first
        $this->assertLessThan(
            expected: $longitudeMinutesPosition,
            actual: $latitudeMinutesPosition,
            message: "Location ISO DDM format ({$format}) doesn't contain the latitude minutes '{$expectedLatitudeMinutes}' first"
        );
    }

    public function oritentationProvider(): Generator
    {
        yield 'origin cardinals' => [0, 0, 'N', 'E'];
        yield 'positive angles cardinals' => [13, 42, 'N', 'E'];
        yield 'negative angles cardinals' => [-13, -42, 'S', 'W'];
    }

    /**
     * @test
     * @dataProvider oritentationProvider
     */
    public function degrees_minutes_format_has_correct_orientation(float $latitude, float $longitude, string $expectedVerticalCardinal, string $expectedHorizontalCardinal): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format
        $format = $location->degreesMinutesFormat();

        // then it should have correct cardinals
        $this->assertStringContainsString(
            needle: $expectedVerticalCardinal,
            haystack: $format,
            message: "Expected the latitude '{$latitude}' to have vertical cardinal '{$expectedVerticalCardinal}' in ISO DDM format"
        );
        $this->assertStringContainsString(
            needle: $expectedHorizontalCardinal,
            haystack: $format,
            message: "Expected the latitude '{$longitude}' to have vertical cardinal '{$expectedHorizontalCardinal}' in ISO DDM format"
        );
    }

    /**
     * @test
     * @dataProvider oritentationProvider
     *
     * @depends degrees_minutes_format_has_correct_orientation
     */
    public function degrees_minutes_format_contains_vertical_cardinal_first(float $latitude, float $longitude, string $expectedVerticalCardinal, string $expectedHorizontalCardinal): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format, and extracting its cardinal
        $format = $location->degreesMinutesFormat();
        $verticalCardinalPosition = strpos(haystack: $format, needle: $expectedVerticalCardinal);
        $horizontalCardinalPosition = strpos(haystack: $format, needle: $expectedHorizontalCardinal);

        // then vertical cardinal should come first
        $this->assertLessThan(
            expected: $horizontalCardinalPosition,
            actual: $verticalCardinalPosition,
            message: "Location ISO DDM format ({$format}) doesn't contain the vertical cardinal {$expectedVerticalCardinal} before the horizontal one {$expectedHorizontalCardinal}"
        );
    }

    public function degreesMinutesFormatProvider(): Generator
    {
        yield 'origin DDM coords' => [0, 0, '00??00.00\'N000??00.00\'E'];
        yield 'positive DDM coords' => [60.0, 45.0, '60??00.00\'N045??00.00\'E'];
        yield 'negative DDM coords' => [-60.0, -45.0, '60??00.00\'S045??00.00\'W'];

        // 0.994 seconds rounds to 0.99 minutes
        yield 'seconds rounding no overflow to minutes' => [0.994 / 60, 0, '00??00.99\'N000??00.00\'E'];

        // 0.995 seconds rounds to 1.00 minute
        $secondsRoundingOverflowAngle = 0.995 / 60;
        yield 'seconds rounding overflow to minutes' => [$secondsRoundingOverflowAngle, 0, '00??01.00\'N000??00.00\'E'];

        // 59.995 minutes, overflow should propagate to degrees and round to 1 degree
        $minutesOverflowAngle = 59 / 60 + $secondsRoundingOverflowAngle;
        yield 'seconds rounding overflow to degrees' => [$minutesOverflowAngle, 0, '01??00.00\'N000??00.00\'E'];
    }

    /**
     * @test
     * @dataProvider degreesMinutesFormatProvider
     */
    public function has_correct_ISO_DDM_format(float $latitude, float $longitude, string $expectedFormat): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees and decimal minutes format
        $format = $location->degreesMinutesFormat();

        // then it should match the expectation
        $this->assertSame(
            expected: $expectedFormat,
            actual: $format,
            message: "Expected ISO DDM format {$expectedFormat}, got {$format}"
        );
    }

    /**
     * @test
     * @dataProvider integerDegreesProvider
     */
    public function degrees_minutes_seconds_format_contains_integer_degrees(float $latitude, float $longitude, int $expectedLatitudeDegrees, int $expectedLongitudeDegrees): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format
        $format = $location->degreesMinutesSecondsFormat();

        // then it should contain integer degrees
        $this->assertStringContainsString(
            needle: "{$expectedLatitudeDegrees}??",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain integer latitude degrees"
        );
        $this->assertStringContainsString(
            needle: "{$expectedLongitudeDegrees}??",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain integer longitude degrees"
        );
    }

    /**
     * @test
     * @dataProvider integerDegreesProvider
     *
     * @depends degrees_minutes_format_contains_integer_degrees
     */
    public function degrees_minutes_seconds_format_contains_latitude_degrees_first(float $latitude, float $longitude, int $expectedLatitudeDegrees, int $expectedLongitudeDegrees): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format and extracting the degrees position
        $format = $location->degreesMinutesSecondsFormat();
        $latitudeDegreesPosition = strpos(haystack: $format, needle: $expectedLatitudeDegrees);
        $longitudeDegreesPosition = strpos(haystack: $format, needle: $expectedLongitudeDegrees);

        // then latitude degrees should come first
        $this->assertLessThan(
            expected: $longitudeDegreesPosition,
            actual: $latitudeDegreesPosition,
            message: "Location ISO DMS format ({$format}) doesn't contain the latitude degrees '{$expectedLatitudeDegrees}' first"
        );
    }

    public function integerMinutesProvider(): Generator
    {
        yield 'positive coords integer minutes' => [13.5, 10.25, 30, 15];
        yield 'negative coords integer minutes' => [-13.5, -10.25, 30, 15];
    }

    /**
     * @test
     * @dataProvider integerMinutesProvider
     */
    public function degrees_minutes_seconds_format_contains_integer_minutes(float $latitude, float $longitude, int $expectedLatitudeMinutes, int $expectedLongitudeMinutes): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format
        $format = $location->degreesMinutesSecondsFormat();

        // then it should contain integer minutes
        $this->assertStringContainsString(
            needle: "{$expectedLatitudeMinutes}'",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain integer latitude minutes"
        );
        $this->assertStringContainsString(
            needle: "{$expectedLongitudeMinutes}'",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain integer longitude minutes"
        );
    }

    /**
     * @test
     * @dataProvider integerMinutesProvider
     *
     * @depends degrees_minutes_format_contains_decimal_minutes
     */
    public function degrees_minutes_seconds_format_contains_latitude_minutes_first(float $latitude, float $longitude, int $expectedLatitudeMinutes, int $expectedLongitudeMinutes): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format and extracting the minutes position
        $format = $location->degreesMinutesSecondsFormat();
        $latitudeMinutesPosition = strpos(haystack: $format, needle: $expectedLatitudeMinutes);
        $longitudeMinutesPosition = strpos(haystack: $format, needle: $expectedLongitudeMinutes);

        // then latitude minutes should come first
        $this->assertLessThan(
            expected: $longitudeMinutesPosition,
            actual: $latitudeMinutesPosition,
            message: "Location ISO DMS format ({$format}) doesn't contain the latitude minutes '{$expectedLatitudeMinutes}' first"
        );
    }

    public function integerSecondsProvider(): Generator
    {
        yield 'positive coords decimal seconds' => [13.34595, 10.252825, 45, 10];
        yield 'negative coords decimal seconds' => [-13.34595, -10.252825, 45, 10];
    }

    /**
     * @test
     * @dataProvider integerSecondsProvider
     */
    public function degrees_minutes_seconds_format_contains_decimal_seconds(float $latitude, float $longitude, float $expectedLatitudeSeconds, float $expectedLongitudeSeconds): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format
        $format = $location->degreesMinutesSecondsFormat();

        // then it should contain decimal seconds
        $this->assertStringContainsString(
            needle: "{$expectedLatitudeSeconds}\"",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain decimal latitude seconds"
        );
        $this->assertStringContainsString(
            needle: "{$expectedLongitudeSeconds}\"",
            haystack: $format,
            message: "Expected ISO DMS format ({$format}) to contain decimal longitude seconds"
        );
    }

    /**
     * @test
     * @dataProvider integerSecondsProvider
     *
     * @depends degrees_minutes_seconds_format_contains_decimal_seconds
     */
    public function degrees_minutes_seconds_format_contains_latitude_seconds_first(float $latitude, float $longitude, float $expectedLatitudeSeconds, float $expectedLongitudeSeconds): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format and extracting the seconds position
        $format = $location->degreesMinutesSecondsFormat();
        $latitudeSecondsPosition = strpos(haystack: $format, needle: $expectedLatitudeSeconds);
        $longitudeSecondsPosition = strpos(haystack: $format, needle: $expectedLongitudeSeconds);


        // then latitude seconds should come first
        $this->assertLessThan(
            expected: $longitudeSecondsPosition,
            actual: $latitudeSecondsPosition,
            message: "Location ISO DMS format ({$format}) doesn't contain the latitude seconds '{$expectedLatitudeSeconds}' first"
        );
    }

    /**
     * @test
     * @dataProvider oritentationProvider
     */
    public function degrees_minutes_seconds_format_has_correct_orientation(float $latitude, float $longitude, string $expectedVerticalCardinal, string $expectedHorizontalCardinal): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format
        $format = $location->degreesMinutesSecondsFormat();

        // then it should have correct cardinals
        $this->assertStringContainsString(
            needle: $expectedVerticalCardinal,
            haystack: $format,
            message: "Expected the latitude '{$latitude}' to have vertical cardinal '{$expectedVerticalCardinal}' in ISO DMS format"
        );
        $this->assertStringContainsString(
            needle: $expectedHorizontalCardinal,
            haystack: $format,
            message: "Expected the latitude '{$longitude}' to have vertical cardinal '{$expectedHorizontalCardinal}' in ISO DMS format"
        );
    }

    /**
     * @test
     * @dataProvider oritentationProvider
     *
     * @depends degrees_minutes_format_has_correct_orientation
     */
    public function degrees_minutes_seconds_format_contains_vertical_cardinal_first(float $latitude, float $longitude, string $expectedVerticalCardinal, string $expectedHorizontalCardinal): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format, and extracting its cardinal
        $format = $location->degreesMinutesSecondsFormat();
        $verticalCardinalPosition = strpos(haystack: $format, needle: $expectedVerticalCardinal);
        $horizontalCardinalPosition = strpos(haystack: $format, needle: $expectedHorizontalCardinal);

        // then vertical cardinal should come first
        $this->assertLessThan(
            expected: $horizontalCardinalPosition,
            actual: $verticalCardinalPosition,
            message: "Location ISO DMS format ({$format}) doesn't contain the vertical cardinal {$expectedVerticalCardinal} before the horizontal one {$expectedHorizontalCardinal}"
        );
    }

    public function degreesMinutesSecondsFormatProvider(): Generator
    {
        yield 'origin DMS coords' => [0, 0, '00??00\'00"N000??00\'00"E'];
        yield 'positive DMS coords' => [60.0, 45.0, '60??00\'00"N045??00\'00"E'];
        yield 'negative DMS coords' => [-60.0, -45.0, '60??00\'00"S045??00\'00"W'];

        // 59.49 seconds rounds to 59 seconds
        yield 'seconds rounding no overflow to minutes' => [59.49 / 3600, 0, '00??00\'59"N000??00\'00"E'];

        // 59.5 seconds rounds to 60 seconds, 1 minute
        $secondsRoundingOverflowAngle = 59.50 / 3600;
        yield 'seconds rounding overflow to minutes' => [$secondsRoundingOverflowAngle, 0, '00??01\'00"N000??00\'00"E'];

        // 59 minutes and 59.5 seconds, overflow should propagate to degrees and round to 1 degree
        $minutesOverflowAngle = 59 / 60 + $secondsRoundingOverflowAngle;
        yield 'seconds rounding overflow to degrees' => [$minutesOverflowAngle, 0, '01??00\'00"N000??00\'00"E'];
    }

    /**
     * @test
     * @dataProvider degreesMinutesSecondsFormatProvider
     */
    public function has_correct_ISO_DMS_format(float $latitude, float $longitude, string $expectedFormat): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its degrees minutes and decimal seconds format
        $format = $location->degreesMinutesSecondsFormat();

        // then it should match the expectation
        $this->assertSame(
            expected: $expectedFormat,
            actual: $format,
            message: "Expected ISO DMS format {$expectedFormat}, got {$format}"
        );
    }

    /**
     * @test
     * @dataProvider validCoordinatesProvider
     */
    public function has_ISO_DMS_format_by_default(float $latitude, float $longitude): void
    {
        // given a valid location
        $location = $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // when checking its default string-format
        $format = (string) $location;

        // then it should match the ISO 6709 DMS format
        $this->assertSame(
            expected: $location->degreesMinutesSecondsFormat(),
            actual: $format,
            message: "Expected default format to be ISO 6709 DMS {$location->degreesMinutesSecondsFormat()}, got {$format}"
        );
    }
}
