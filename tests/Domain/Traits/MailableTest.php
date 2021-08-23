<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Identity;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Traits\Mailable;
use Alphonse\CleanArchBootstrap\Domain\Traits\MailableInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesMailAddress;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Traits\Mailable
 */
final class MailableTest extends TestCase
{
    use CreatesMailAddress;

    private MailAddressInterface $mailAddress;

    public function setUp(): void
    {
        $this->mailAddress = $this->createRealMailAddress();
    }

    /**
     * @return MailableInterface - an object with a mail address
     */
    private function createInstance(MailAddressInterface $mailAddress): MailableInterface
    {
        return new class($mailAddress) implements MailableInterface {
            use Mailable;

            public function __construct(private MailAddressInterface $mailAddress)
            {
            }
        };
    }

    /**
     * @test
     * @covers ::getMailAddress
     */
    public function returns_mail_address(): void
    {
        // given an object with a mail address
        $owner = $this->createInstance(mailAddress: $this->mailAddress);

        // when requesting the object's identity
        $storedMailAddress = $owner->getMailAddress();

        // then it should be the one given at construction
        $this->assertSame(
            expected: $this->mailAddress,
            actual: $storedMailAddress,
            message: "The object returned the wrong mail address, expected '{$this->mailAddress}', got '{$storedMailAddress}'",
        );
    }
}
