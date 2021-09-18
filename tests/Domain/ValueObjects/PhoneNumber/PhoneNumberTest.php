<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\PhoneNumber;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumberInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesPhoneNumber;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesDummyValueObject;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\PhoneNumber\PhoneNumber
 */
final class PhoneNumberTest extends TestCase
{
    use CreatesDummyValueObject;
    use CreatesPhoneNumber;

    public function valueObjectProvider(): Generator
    {
        $countryIdentifier = '42';
        $localNumber = '666';

        $phoneNumber = $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier, localNumber: $localNumber);

        yield 'not a phone number' => [
            $phoneNumber,
            $this->createDummyValueObject(),
            false
        ];
        yield 'phone number with different country identifier' => [
            $phoneNumber,
            $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier . '3', localNumber: $localNumber),
            false
        ];
        yield 'phone number with different local number' => [
            $phoneNumber,
            $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier, localNumber: $localNumber . '3'),
            false
        ];
        yield 'same number' => [
            $phoneNumber,
            $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier, localNumber: $localNumber),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_number(PhoneNumberInterface $number, ValueObjectInterface $other, bool $expectedEquality): void
    {
        // given a value object to compare with

        // when comparing the 2 instances
        $areSameValue = $number->equals($other);

        // when it should match the expected equality
        $this->assertSame(
            expected: $expectedEquality,
            actual: $areSameValue,
        );
    }

    public function phoneNumberProvider(): Generator
    {
        yield 'french mobile number' => ['33', '601020304'];
    }

    /**
     * @test
     * @dataProvider phoneNumberProvider
     */
    public function national_format_contains_local_number(string $_, string $localNumber): void
    {
        // given a local number and a PhoneNumber object made from it
        $phoneNumber = $this->createRealPhoneNumber(localNumber: $localNumber);

        // when checking its national format
        $nationalFormat = $phoneNumber->toNationalFormat();

        // then it should contain the local number
        $this->assertStringContainsString(
            needle: $localNumber,
            haystack: $nationalFormat,
            message: "Phone number's national format '{$nationalFormat}' doesn't contain the local number '{$localNumber}'",
        );
    }

    /**
     * @test
     * @dataProvider phoneNumberProvider
     */
    public function international_format_starts_with_a_plus_sign(string $countryIdentifier, string $localNumber): void
    {
        // given a valid phone number
        $phoneNumber = $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier, localNumber: $localNumber);

        // when checking the internation representation
        $internationalFormat = $phoneNumber->toInternationalFormat();

        // then it should start with a '+' sign
        $this->assertStringStartsWith(
            prefix: '+',
            string: $internationalFormat,
            message: "The international format '{$internationalFormat}' doesn't start with a '+' sign",
        );
    }

    /**
     * @test
     * @dataProvider phoneNumberProvider
     */
    public function international_format_contains_country_identifier(string $countryIdentifier, string $_): void
    {
        // given a country identifier and a PhoneNumber made from it
        $phoneNumber = $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier);

        // when checking its internation format
        $internationalFormat = $phoneNumber->toInternationalFormat();

        // then it should contain the country identifier
        $this->assertStringContainsString(
            needle: $countryIdentifier,
            haystack: $internationalFormat,
            message: "Phone number's international format '{$internationalFormat}' doesn't contain the country identifier '{$countryIdentifier}'"
        );
    }



    /**
     * @test
     * @dataProvider phoneNumberProvider
     */
    public function international_format_contains_local_number(string $_, string $localNumber): void
    {
        // given a local number and a PhoneNumber made from it
        $phoneNumber = $this->createRealPhoneNumber(localNumber: $localNumber);

        // when checking its internation format
        $internationalFormat = $phoneNumber->toInternationalFormat();

        // then it should contain the local number
        $this->assertStringContainsString(
            needle: $localNumber,
            haystack: $internationalFormat,
            message: "Phone number's international format '{$internationalFormat}' doesn't contain the local number '{$localNumber}'"
        );
    }

    /**
     * @test
     * @dataProvider phoneNumberProvider
     */
    public function international_format_is_used_by_default(string $countryIdentifier, string $localNumber): void
    {
        // given a valid phone number object
        $phoneNumberObject = $this->createRealPhoneNumber(countryIdentifier: $countryIdentifier, localNumber: $localNumber);

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
}
