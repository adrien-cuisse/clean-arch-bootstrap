<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Traits\MailableInterface;

/**
 * An user of the application
 */
interface UserInterface extends EntityInterface, MailableInterface
{
}
