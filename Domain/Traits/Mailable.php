<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;

trait Mailable
{
    private EmailInterface $email;

    /**
     * @see MailableInterface
     */
    public function getEmail(): EmailInterface
    {
        return $this->email;
    }
}
