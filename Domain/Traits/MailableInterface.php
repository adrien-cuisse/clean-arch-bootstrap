<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress\MailAddressInterface;

interface MailableInterface
{
    public function mailAddress(): MailAddressInterface;
}
