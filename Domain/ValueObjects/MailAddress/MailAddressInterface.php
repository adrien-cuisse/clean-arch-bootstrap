<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\MailAddress;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Stringable;

/**
 * A mail address
 */
interface MailAddressInterface extends Stringable, ValueObjectInterface
{
}
