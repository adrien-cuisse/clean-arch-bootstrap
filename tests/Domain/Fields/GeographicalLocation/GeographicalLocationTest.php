<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\GeographicalLocation;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesGeographicalLocation;
use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\InvalidLatitudeException;
use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\InvalidLongitudeException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocation
 */
final class GeographicalLocationTest extends TestCase
{
    use CreatesGeographicalLocation;

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
     *
     * @doesNotPerformAssertions
     */
    public function has_valid_ranged_coordinates(float $latitude, float $longitude): void
    {
        // given valid coordinates

        // when creating a location from them
        $this->createRealGeographicalLocation(latitude: $latitude, longitude: $longitude);

        // then they should be accepted
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

    public function degreesMinutesFormatProvider(): Generator
    {
        yield 'origin DDM coords' => [0, 0, '00°00.00\'N000°00.00\'E'];
        yield 'positive DDM coords' => [60.0, 45.0, '60°00.00\'N045°00.00\'E'];
        yield 'negative DDM coords' => [-60.0, -45.0, '60°00.00\'S045°00.00\'W'];
        yield '2 decimals rounding DDM coords' => [0.00205760102, 0.007613150102, '00°00.12\'N000°00.46\'E'];
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

    public function degreesMinutesSecondsFormatProvider(): Generator
    {
        yield 'origin DMS coords' => [0, 0, '00°00\'00"N000°00\'00"E'];
        yield 'positive DMS coords' => [60.0, 45.0, '60°00\'00"N045°00\'00"E'];
        yield 'negative DMS coords' => [-60.0, -45.0, '60°00\'00"S045°00\'00"W'];

        $angleSecond = 1 / 3600;
        yield '0 decimals rounding DMS coords' => [$angleSecond, $angleSecond / 2,  '00°00\'01"N000°00\'01"E'];
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
}
