<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesPhoneNumberFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactory
 */
final class PhoneNumberFactoryTest extends TestCase
{
    use CreatesPhoneNumberFactory;

    private PhoneNumberFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealPhoneNumberFactory();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::withCountryIdentifier
     * @covers ::build
     */
    public function created_number_has_assigned_country_identifier(): void
    {
        // given a country identifier
        $countryIdentifier = 'country code';

        // when creating a PhoneNumber object from it
        $phoneNumber = $this->factory
            ->withCountryIdentifier(countryIdentifier: $countryIdentifier)
            ->withLocalNumber(localNumber: '')
            ->build();

        // then the created PhoneNumber object should have the given country identifier
        $internationalFormat = $phoneNumber->toInternationalFormat();
        $countryIdentifierPositionInInternationalFormat = strpos(haystack: $internationalFormat, needle: $countryIdentifier);
        $internationalFormatContainsCountryIdentifier = ($countryIdentifierPositionInInternationalFormat !== false);
        $this->assertTrue(
            condition: $internationalFormatContainsCountryIdentifier,
            message: "Created phone number should have the given country identifier '{$countryIdentifier}'",
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::withLocalNumber
     * @covers ::build
     */
    public function created_number_has_assigned_local_number(): void
    {
        // given a local number
        $localNumber = 'local number';

        // when creating a PhoneNumber object from it
        $phoneNumber = $this->factory
            ->withCountryIdentifier(countryIdentifier: '')
            ->withLocalNumber(localNumber: $localNumber)
            ->build();

        // then the created PhoneNumber object should have the given local number
        $internationalFormat = (string) $phoneNumber;
        $localNumberPositionInInternationalFormat = strpos(haystack: $internationalFormat, needle: $localNumber);
        $internationalFormatContainsLocalNumber = ($localNumberPositionInInternationalFormat !== false);
        $this->assertTrue(
            condition: $internationalFormatContainsLocalNumber,
            message: "Created phone number should have the given local number '{$localNumber}'",
        );
    }
}
