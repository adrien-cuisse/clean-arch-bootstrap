<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimestampHighBytesCountException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidTimestampHighBytesCountException
 */
final class InvalidUuidTimestampHighBytesCountExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some bytes
        $bytes = [0xc0, 0xff, 0xee];
        $exception = new InvalidUuidTimestampHighBytesCountException(bytes: $bytes);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        $this->assertStringContainsString(
            needle: implode(', ', $bytes),
            haystack: $errorMessage,
            message: "Exception should show incriminated bytes in error message"
        );
    }
}
