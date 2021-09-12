<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\InvalidLongitudeException;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesGeographicalLocation;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\InvalidLongitudeException
 */
final class InvalidLongitudeExceptionTest extends TestCase
{
    use CreatesGeographicalLocation;

    /**
     * @test
     */
    public function shows_latitude_in_message(): void
    {
        // given some invalid longitude
        $invalidLongitude = 666;

        // when using it to create an exception
        $exception = new InvalidLongitudeException($invalidLongitude);

        // then its error message should contain the invalid longitude
        $this->assertStringContainsString(
            needle: $invalidLongitude,
            haystack: $exception->getMessage(),
            message: "The error message should contain the invalid longitude '{$invalidLongitude}'",
        );
    }
}
