<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddress;
use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

trait CreatesMailAddress
{
    private function createRealMailAddress(string $mailAddress = 'foo@bar.org'): MailAddressInterface
    {
        return new MailAddress(mailAddress: $mailAddress);
    }

    private function createFakeMailAddress(): MailAddressInterface
    {
        $mailAddress = $this->getMock(MailAddressInterface::class);
        $mailAddress->method('__toString')->willReturn('mail address');

        return $mailAddress;
    }
}
