<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\PhoneNumber;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesPhoneNumber;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Fields\PhoneNumber\PhoneNumber
 */
final class PhoneNumberTest extends TestCase
{
    use CreatesPhoneNumber;

    /**
     * @test
     * @covers ::__toString
     * @covers ::__construct
     */
    public function stores_country_identifier_used_at_creation(): void
    {
        // given a valid country identifier and a PhoneNumber object made from it
        $countryIdentifier = '33';
        $phoneNumberObject = $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier);

        // when checking the default string-representation
        $phoneNumberString = (string) $phoneNumberObject;

        // then it should contain the country identifier used at creation
        $this->assertStringContainsString(
            needle: $countryIdentifier,
            haystack: $phoneNumberString,
            message: "Phone number object '{$phoneNumberString}' doesn't contain the country identifier '{$countryIdentifier}'",
        );
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::__construct
     */
    public function stores_local_number_used_at_creation(): void
    {
        // given a valid local number and a PhoneNumber object made from it
        $localNumber = '123456';
        $phoneNumberObject = $this->createRealPhoneNumber(localNumber: $localNumber);

        // when checking the default string-representation
        $phoneNumberString = (string) $phoneNumberObject;

        // then it should contain the local number used at creation
        $this->assertStringContainsString(
            needle: $localNumber,
            haystack: $phoneNumberString,
            message: "Phone number object '{$phoneNumberString}' doesn't contain the local number '{$localNumber}'",
        );
    }

    /**
     * @test
     * @covers ::toNationalFormat
     */
    public function national_format_contains_local_number(): void
    {
        // given a valid local number and a PhoneNumber object made from it
        $localNumber = '123456';
        $phoneNumberObject = $this->createRealPhoneNumber(localNumber: $localNumber);

        // when checking the national format
        $nationalFormat = $phoneNumberObject->toNationalFormat();

        // then it should contain the local number used at creation
        $this->assertStringContainsString(
            needle: $localNumber,
            haystack: $nationalFormat,
            message: "Phone number's national format '{$nationalFormat}' doesn't contain the local number '{$localNumber}'",
        );
    }

    /**
     * @test
     * @covers ::__toString
     */
    public function international_format_is_used_by_default(): void
    {
        // given a valid phone number object
        $phoneNumberObject = $this->createRealPhoneNumber();

        // when checking the default string-representation
        $phoneNumberString = (string) $phoneNumberObject;

        // then it should be the international format
        $internationalFormat = $phoneNumberObject->toInternationalFormat();
        $this->assertSame(
            expected: $internationalFormat,
            actual: $phoneNumberString,
            message: "Phone number '{$phoneNumberString}' doesn't have the international format '{$internationalFormat}' by default",
        );
    }

    /**
     * @test
     * @covers ::toInternationalFormat
     */
    public function international_format_starts_with_a_plus_sign(): void
    {
        // given a valid phone number object
        $phoneNumberObject = $this->createRealPhoneNumber();

        // when checking the internation representation
        $internationalFormat = $phoneNumberObject->toInternationalFormat();

        // then it should contain a '+' sign
        $this->assertStringStartsWith(
            prefix: '+',
            string: $internationalFormat,
            message: "The international format '{$internationalFormat}' doesn't start with a '+' sign",
        );
    }
}
