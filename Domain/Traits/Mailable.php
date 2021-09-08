<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\MailAddress\MailAddressInterface;

trait Mailable
{
    private MailAddressInterface $mailAddress;

    /**
     * @see MailableInterface
     */
    public function mailAddress(): MailAddressInterface
    {
        return $this->mailAddress;
    }
}
