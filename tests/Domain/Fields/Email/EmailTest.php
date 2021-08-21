<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Email;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\InvalidMailException;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesEmail;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email
 * @uses Alphonse\CleanArchBootstrap\Domain\Fields\Email\InvalidMailException
 */
final class EmailTest extends TestCase
{
    use CreatesEmail;

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
        $this->createRealEmail(email: $invalidMailAddress);

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
        $emailObject = $this->createRealEmail   (email: $emailString);

        // when checking the stored string
        $storedEmailString = (string) $emailObject;

        $this->assertEquals(
            expected: $emailString,
            actual: $storedEmailString,
            message: "Email object returned the wrong email-string, expected {$emailString}, got {$storedEmailString}"
        );
    }
}
