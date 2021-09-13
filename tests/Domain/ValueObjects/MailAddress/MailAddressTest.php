<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\MailAddress;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesMailAddress;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesDummyValueObject;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\InvalidMailAddressException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddress
 */
final class MailAddressTest extends TestCase
{
    use CreatesDummyValueObject;
    use CreatesMailAddress;

    public function valueObjectProvider(): Generator
    {
        $address = $this->createRealMailAddress();

        yield 'different type of object' => [
            $address,
            $this->createDummyValueObject(),
            false
        ];
        yield 'same type of object with different properties' => [
            $address,
            $this->createRealMailAddress(mailAddress: 'different@email.org'),
            false
        ];
        yield 'same type of object with same properties' => [
            $address,
            $this->createRealMailAddress(),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_address(MailAddressInterface $number, ValueObjectInterface $other, bool $expectedEquality): void
    {
        // given a value object to compare with

        // when comparing the 2 instances
        $areSameValue = $number->equals($other);

        // when it should match the expected equality
        $this->assertSame(
            expected: $expectedEquality,
            actual: $areSameValue,
        );
    }

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
