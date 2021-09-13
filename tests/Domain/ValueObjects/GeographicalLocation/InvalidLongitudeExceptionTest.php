<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLongitudeException;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesGeographicalLocation;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLongitudeException
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
