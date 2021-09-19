<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressInterface;

interface MailableInterface
{
    public function mailAddress(): MailAddressInterface;
}
