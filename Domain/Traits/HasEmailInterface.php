<?php

namespace Alphonse\CleanArch\Domain\Traits;

use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;

interface HasEmailInterface
{
    public function getEmail(): EmailInterface;
}
