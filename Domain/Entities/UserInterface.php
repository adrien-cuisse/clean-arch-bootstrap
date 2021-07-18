<?php

namespace Alphonse\CleanArch\Domain\Entities;

use Alphonse\CleanArch\Domain\Traits\HasEmailInterface;

/**
 * An user of the application
 */
interface UserInterface extends EntityInterface, HasEmailInterface
{
}
