<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber;

interface PhoneableInterface
{
    public function phoneNumber(): PhoneNumberInterface;
}
