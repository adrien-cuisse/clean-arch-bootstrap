<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber;

use Stringable;

/**
 * A phone number
 */
interface PhoneNumberInterface extends Stringable
{
    public function toNationalFormat(): string;

    public function toInternationalFormat(): string;
}
