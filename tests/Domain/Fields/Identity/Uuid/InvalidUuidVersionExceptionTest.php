<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity\Uuid;

use Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidVersionException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Identity\Uuid\InvalidUuidVersionException
 */
final class InvalidUuidVersionExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some uuid version
        $version = 42;
        $exception = new InvalidUuidVersionException(version: $version);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        $this->assertStringContainsString(
            needle: $version,
            haystack: $errorMessage,
            message: "Exception should show incriminated version in error message"
        );
    }
}
