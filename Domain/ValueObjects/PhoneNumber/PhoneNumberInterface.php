<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

use Stringable;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;

interface PhoneNumberInterface extends Stringable, ValueObjectInterface
{
    public function nationalFormat(): string;

    public function internationalFormat(): string;
}
