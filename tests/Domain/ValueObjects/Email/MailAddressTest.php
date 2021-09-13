<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\MailAddress;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesMailAddress;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\InvalidMailAddressException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddress
 */
final class MailAddressTest extends TestCase
{
    use CreatesMailAddress;

    /**
     * @test
     */
    public function rejects_invalid_mail_address(): void
    {
        $this->expectException(InvalidMailAddressException::class);

        // given an invalid mail address
        $invalidMailAddress = 'not a valid mail address';

        // when trying to create a MailAddress from it
        $this->createRealMailAddress($invalidMailAddress);

        // then it should throw an exception
    }

    /**
     * @test
     */
    public function stores_email_used_at_creation(): void
    {
        // given a valid mail-string and a MailAddress object made from it
        $mailAddressString = 'some@email.org';
        $mailAddressObject = $this->createRealMailAddress($mailAddressString);

        // when checking the stored string
        $storedMailAddressString = (string) $mailAddressObject;

        // then it should be the one given at creation
        $this->assertSame(
            expected: $mailAddressString,
            actual: $storedMailAddressString,
            message: "MailAddress object returned the wrong email-string, expected {$mailAddressString}, got {$storedMailAddressString}"
        );
    }
}
