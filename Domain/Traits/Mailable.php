<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

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
