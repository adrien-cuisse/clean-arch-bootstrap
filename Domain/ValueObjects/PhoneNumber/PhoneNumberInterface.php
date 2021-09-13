<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

use Stringable;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

/**
 * A phone number
 */
interface PhoneNumberInterface extends Stringable, ValueObjectInterface
{
    public function toNationalFormat(): string;

    public function toInternationalFormat(): string;
}
