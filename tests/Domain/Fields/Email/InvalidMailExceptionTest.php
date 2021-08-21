<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Email;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\InvalidMailException;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\Email\InvalidMailException
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
        $errorMessageContainsMailAddress = ($emailPositionInMessage !== false);

        // then it shouldn't be false
        $this->assertTrue(
            condition: $errorMessageContainsMailAddress,
            message: 'The exception should store the given mail address in the error message'
        );
    }
}
