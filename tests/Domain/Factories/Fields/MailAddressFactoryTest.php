<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\MailAddressFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesMailAddressFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\Factories\Fields\MailAddressFactory
 */
final class MailAddressFactoryTest extends TestCase
{
    use CreatesMailAddressFactory;

    private MailAddressFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealMailAddressFactory();
    }

    /**
     * @test
     */
    public function created_mail_address_has_given_address(): void
    {
        // given a mail address
        $mailAddressString = 'foo@bar.org';

        // when creating an MailAddress object from it
        $mailAddressObject = $this->factory->withMailAddress($mailAddressString)->build();

        // then the created MailAddress object should match the given mail address
        $this->assertSame(
            expected: $mailAddressString,
            actual: (string) $mailAddressObject,
            message: "Created MailAddress should have the given address '{$mailAddressString}', got '{$mailAddressObject}'",
        );
    }
}
