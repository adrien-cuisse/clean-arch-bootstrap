<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Email;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Fields\Email\InvalidMailException;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Email\InvalidMailException
 */
final class InvalidMailExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_email_in_message(): void
    {
        // given some mail address used to create the exception
        $invalidEmail = 'invalid@email.org';
        $exception = new InvalidMailException(email: $invalidEmail);

        // when checking the position of the address in the message
        $emailPositionInMessage = strpos(
            haystack: $exception->getMessage(),
            needle: $invalidEmail
        );

        // then it shouldn't be false
        $this->assertNotFalse(
            condition: $emailPositionInMessage,
            message: 'The exception should store the given mail address in the error message'
        );
    }
}
