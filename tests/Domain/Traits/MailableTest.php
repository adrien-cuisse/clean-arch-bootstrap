<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Identity;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Traits\Mailable;
use Alphonse\CleanArchBootstrap\Domain\Traits\MailableInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Traits\Mailable
 */
final class MailableTest extends TestCase
{
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

    private function createMailAddress(): MailAddressInterface
    {
        return new class implements MailAddressInterface {
            public function __toString()
            {
                return 'mail address';
            }
        };
    }

    /**
     * @test
     * @covers ::getMailAddress
     */
    public function returns_mail_address(): void
    {
        // given a new MailAddress and an object having it
        $mailAddress = $this->createMailAddress();
        $owner = $this->createInstance(mailAddress: $mailAddress);

        // when requesting the object's identity
        $storedMailAddress = $owner->getMailAddress();

        // then it should be the one given at construction
        $this->assertSame(
            expected: $mailAddress,
            actual: $storedMailAddress,
            message: "The object returned the wrong mail address, expected '{$mailAddress}', got '{$storedMailAddress}'",
        );
    }
}
