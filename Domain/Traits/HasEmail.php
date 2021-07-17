<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;

trait HasEmail
{
    private EmailInterface $email;

    /**
     * @see HasEmailInterface
     */
    public function getEmail(): EmailInterface
    {
        return $this->email;
    }
}
