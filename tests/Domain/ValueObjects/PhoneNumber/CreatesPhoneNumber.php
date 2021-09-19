<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\PhoneNumber;

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
        $phoneNumber->method('nationalFormat')->willReturn('0000000000');
        $phoneNumber->method('internationalFormat')->willReturn('+33000000000');
        $phoneNumber->method('__toString')->willReturn('+33000000000');

        return $phoneNumber;
    }
}
