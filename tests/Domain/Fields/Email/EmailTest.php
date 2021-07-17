<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Email;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Fields\Email\Email;
use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;
use Alphonse\CleanArch\Domain\Fields\Email\InvalidMailException;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Fields\Email\Email
 * @uses Alphonse\CleanArch\Domain\Fields\Email\InvalidMailException::__construct
 */
final class EmailTest extends TestCase
{
    /**
     * @return UuidInterface - an instance to test
     */
    private function createInstance(string $email): EmailInterface
    {
        return new Email(email: $email);
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function rejects_invalid_email(): void
    {
        $this->expectException(InvalidMailException::class);

        // given an invalid mail address
        $invalidMailAddress = 'not a valid mail address';

        // when
        $this->createInstance(email: $invalidMailAddress);

        // then it should throw an exception
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::__construct
     */
    public function stores_email_used_at_creation(): void
    {
        // given a valid mail-string and an Email object made from it
        $emailString = 'some@email.org';
        $emailObject = $this->createInstance(email: $emailString);

        // when checking the stored string
        $storedEmailString = (string) $emailObject;

        $this->assertEquals(
            expected: $emailString,
            actual: $storedEmailString,
            message: "Email object returned the wrong email-string, expected {$emailString}, got {$storedEmailString}"
        );
    }
}
