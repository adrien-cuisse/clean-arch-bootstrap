<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;

interface MailableInterface
{
    public function getEmail(): EmailInterface;
}
