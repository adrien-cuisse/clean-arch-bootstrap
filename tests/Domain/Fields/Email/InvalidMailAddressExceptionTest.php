<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\MailAddress;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\InvalidMailAddressException;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\InvalidMailAddressException
 */
final class InvalidMailAddressExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function shows_mail_address_in_message(): void
    {
        // given some mail address used to create the exception
        $invalidMailAddress = 'invalid@email.org';
        $exception = new InvalidMailAddressException(mailAddress: $invalidMailAddress);

        // when checking the position of the address in the message
        $emailPositionInMessage = strpos(
            haystack: $exception->getMessage(),
            needle: $invalidMailAddress
        );

        // then it shouldn't be false
        $errorMessageContainsMailAddress = ($emailPositionInMessage !== false);
        $this->assertTrue(
            condition: $errorMessageContainsMailAddress,
            message: 'The exception should store the given mail address in the error message'
        );
    }
}
