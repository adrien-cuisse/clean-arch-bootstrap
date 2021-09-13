<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Identity\Uuid;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidVersionException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\InvalidUuidVersionException
 */
final class InvalidUuidVersionExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some uuid version
        $version = 42;
        $exception = new InvalidUuidVersionException($version);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        $this->assertStringContainsString(
            needle: $version,
            haystack: $errorMessage,
            message: "Exception should show incriminated version '{$version}' in error message",
        );
    }
}
