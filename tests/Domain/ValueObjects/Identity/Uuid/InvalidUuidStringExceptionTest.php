<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Identity\Uuid;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidStringException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidStringException
 */
final class InvalidUuidStringExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some some uuid-string
        $uuidString = 'some unique identifier';
        $exception = new InvalidUuidStringException($uuidString);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        $this->assertStringContainsString(
            needle: $uuidString,
            haystack: $errorMessage,
            message: "Exception should show incriminated uuid-string '{$uuidString}' in error message",
        );
    }
}
