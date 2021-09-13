<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLatitudeException;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesGeographicalLocation;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\InvalidLatitudeException
 */
final class InvalidLatitudeExceptionTest extends TestCase
{
    use CreatesGeographicalLocation;

    /**
     * @test
     */
    public function shows_latitude_in_message(): void
    {
        // given some invalid latitude
        $invalidLatidude = 666;

        // when using it to create an exception
        $exception = new InvalidLatitudeException($invalidLatidude);

        // then its error message should contain the invalid latitude
        $this->assertStringContainsString(
            needle: $invalidLatidude,
            haystack: $exception->getMessage(),
            message: "The error message should contain the invalid latitude '{$invalidLatidude}'",
        );
    }
}
