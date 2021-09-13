<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\ValueObjects;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\GeographicalLocationFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\ValueObjects\CreatesGeographicalLocationFactory;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects\GeographicalLocationFactory
 */
final class GeographicalLocationFactoryTest extends TestCase
{
    use CreatesGeographicalLocationFactory;

    private GeographicalLocationFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealGeographicalLocationFactory()
            ->withLatitude(0)
            ->withLongitude(0);
    }

    /**
     * @test
     */
    public function created_location_has_given_latitude(): void
    {
        // given a latitude
        $latitude = 3.14;

        // when creating a location from it
        $location = $this->factory->withLatitude($latitude)->build();

        // then the created location's latitude should match the given one
        $this->assertSame(
            expected: $latitude,
            actual: $location->latitude(),
            message: "Created location should have the given latitude '{$latitude}', got '{$location->latitude()}'",
        );
    }

    /**
     * @test
     */
    public function created_location_has_given_longitude(): void
    {
        // given a longitude
        $longitude = 0.618;

        // when creating a location from it
        $location = $this->factory->withLongitude($longitude)->build();

        // then the created location's longitude should match the given one
        $this->assertSame(
            expected: $longitude,
            actual: $location->longitude(),
            message: "Created location should have the given longitude '{$longitude}', got '{$location->longitude()}'",
        );
    }
}
