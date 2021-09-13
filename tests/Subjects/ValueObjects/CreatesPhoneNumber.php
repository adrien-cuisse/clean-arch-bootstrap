<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumber;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberInterface;

trait CreatesPhoneNumber
{
    private function createRealPhoneNumber(string $countryIdentifier = '33', string $localNumber = '312345678'): PhoneNumberInterface
    {
        return new PhoneNumber(
            countryIdentifier: $countryIdentifier,
            localNumber: $localNumber
        );
    }

    private function createFakeEmail(): PhoneNumberInterface
    {
        $phoneNumber = $this->getMock(PhoneNumberInterface::class);
        $phoneNumber->method('toNationalFormat')->willReturn('0000000000');
        $phoneNumber->method('toInternationalFormat')->willReturn('+33000000000');
        $phoneNumber->method('__toString')->willReturn('+33000000000');

        return $phoneNumber;
    }
}
