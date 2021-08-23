<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesPhoneNumberFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\PhoneNumberFactory
 */
final class PhoneNumberFactoryTest extends TestCase
{
    use CreatesPhoneNumberFactory;

    /**
     * @test
     * @covers ::withCountryIdentifier
     * @covers ::build
     */
    public function created_number_has_assigned_country_identifier(): void
    {
        // given a new factory
        $factory = $this->createRealPhoneNumberFactory();

        // when assigning a country identifier to the phone number
        $phoneNumber = $factory
            ->withCountryIdentifier(countryIdentifier: 'country code')
            ->withLocalNumber(localNumber: '')
            ->build();
            
        // then the created phone number should contain the assigned country identifier
        $internationalFormat = $phoneNumber->toInternationalFormat();
        $countryIdentifierPositionInInternationalFormat = strpos(haystack: $internationalFormat, needle: 'country code');
        $internationalFormatContainsCountryIdentifier = ($countryIdentifierPositionInInternationalFormat !== false);
        $this->assertTrue(
            condition: $internationalFormatContainsCountryIdentifier,
            message: "Created phone number should have the given country identifier"
        );
    }

    /**
     * @test
     * @covers ::withLocalNumber
     * @covers ::build
     */
    public function created_number_has_assigned_local_number(): void
    {
        // given a new factory
        $factory = $this->createRealPhoneNumberFactory();

        // when assigning a local number to the phone number
        $phoneNumber = $factory
            ->withCountryIdentifier(countryIdentifier: '')
            ->withLocalNumber(localNumber: 'local number')
            ->build();
            
        // then the created phone number should contain the assigned country identifier
        $internationalFormat = (string) $phoneNumber;
        $localNumberPositionInInternationalFormat = strpos(haystack: $internationalFormat, needle: 'local number');
        $internationalFormatContainsLocalNumber = ($localNumberPositionInInternationalFormat !== false);
        $this->assertTrue(
            condition: $internationalFormatContainsLocalNumber,
            message: "Created phone number should have the given local number"
        );
    }
}
