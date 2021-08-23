<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

interface MailableInterface
{
    public function getMailAddress(): MailAddressInterface;
}
