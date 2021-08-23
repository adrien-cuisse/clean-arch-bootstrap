<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\MailAddress;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesMailAddress;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\InvalidMailAddressException;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddress
 */
final class MailAddressTest extends TestCase
{
    use CreatesMailAddress;

    /**
     * @test
     * @covers ::__construct
     */
    public function rejects_invalid_mail_address(): void
    {
        $this->expectException(InvalidMailAddressException::class);

        // given an invalid mail address
        $invalidMailAddress = 'not a valid mail address';

        // when
        $this->createRealMailAddress(mailAddress: $invalidMailAddress);

        // then it should throw an exception
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::__construct
     */
    public function stores_email_used_at_creation(): void
    {
        // given a valid mail-string and an MailAddress object made from it
        $mailAddressString = 'some@email.org';
        $mailAddressObject = $this->createRealMailAddress(mailAddress: $mailAddressString);

        // when checking the stored string
        $storedMailAddressString = (string) $mailAddressObject;

        $this->assertEquals(
            expected: $mailAddressString,
            actual: $storedMailAddressString,
            message: "MailAddress object returned the wrong email-string, expected {$mailAddressString}, got {$storedMailAddressString}"
        );
    }
}
