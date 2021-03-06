<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\PhoneNumber;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberFactoryInterface;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberFactory
 */
final class PhoneNumberFactoryTest extends TestCase
{
    use CreatesPhoneNumberFactory;

    private PhoneNumberFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealPhoneNumberFactory()
            ->withCountryIdentifier('')
            ->withLocalNumber('');
    }

    /**
     * @test
     */
    public function created_number_has_assigned_country_identifier(): void
    {
        // given a country identifier
        $countryIdentifier = 'country code';

        // when creating a PhoneNumber object from it
        $phoneNumberObject = $this->factory->withCountryIdentifier($countryIdentifier)->build();

        // then the created PhoneNumber object should have the given country identifier
        $phoneNumberString = (string) $phoneNumberObject;
        $this->assertStringContainsString(
            needle: $countryIdentifier,
            haystack: $phoneNumberString,
            message: "Created phone number should have the given country identifier '{$countryIdentifier}'",
        );
    }

    /**
     * @test
     */
    public function created_number_has_assigned_local_number(): void
    {
        // given a local number
        $localNumber = 'local number';

        // when creating a PhoneNumber object from it
        $phoneNumberObject = $this->factory->withLocalNumber($localNumber)->build();

        // then the created PhoneNumber object should have the given local number
        $phoneNumberString = (string) $phoneNumberObject;
        $this->assertStringContainsString(
            needle: $localNumber,
            haystack: $phoneNumberString,
            message: "Created phone number should have the given local number '{$localNumber}'",
        );
    }
}
