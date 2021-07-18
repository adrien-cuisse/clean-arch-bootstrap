<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidStringException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidStringException
 */
final class InvalidUuidStringExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some some uuid-string
        $uuidString = 'some unique identifier';
        $exception = new InvalidUuidStringException(uuidString: $uuidString);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        $this->assertStringContainsString(
            needle: $uuidString,
            haystack: $errorMessage,
            message: "Exception should show incriminated uuid-string in error message"
        );
    }
}
