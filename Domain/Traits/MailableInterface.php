<?php

namespace Alphonse\CleanArchBootstrap\Domain\Traits;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

interface MailableInterface
{
    public function getEmail(): EmailInterface;
}
