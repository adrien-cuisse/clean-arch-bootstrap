<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Identity\Uuid;

use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\InvalidUuidNodeBytesCountException
 */
final class InvalidUuidNodeBytesCountExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_bytes_in_error_message(): void
    {
        // given an exception for some bytes
        $bytes = [0xc0, 0xff, 0xee];
        $exception = new InvalidUuidNodeBytesCountException(bytes: $bytes);

        // when checking its error message
        $errorMessage = $exception->getMessage();

        // then it should contain incriminated bytes
        $bytesListString = implode(separator: ', ', array: $bytes);
        $this->assertStringContainsString(
            needle: $bytesListString,
            haystack: $errorMessage,
            message: "Exception should show incriminated bytes '{$bytesListString}' in error message",
        );
    }
}
