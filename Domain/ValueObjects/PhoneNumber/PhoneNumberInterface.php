<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

use Stringable;

/**
 * A phone number
 */
interface PhoneNumberInterface extends Stringable
{
    public function toNationalFormat(): string;

    public function toInternationalFormat(): string;
}
