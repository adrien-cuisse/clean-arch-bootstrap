<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\MailAddress;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\InvalidMailAddressException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\InvalidMailAddressException
 */
final class InvalidMailAddressExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shows_mail_address_in_message(): void
    {
        // given some mail address used to create the exception
        $invalidMailAddress = 'invalid@email.org';
        $exception = new InvalidMailAddressException($invalidMailAddress);

        // when checking the error message
        $errorMessage = $exception->getMessage();

        // then it should contain the invalid mail address
        $this->assertStringContainsString(
            needle: $invalidMailAddress,
            haystack: $errorMessage,
            message: "The error message should contain the invalid mail address '{$invalidMailAddress}'",
        );
    }
}
