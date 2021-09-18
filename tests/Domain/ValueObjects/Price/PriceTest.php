<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Price;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesPrice;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\ValueObjects\CreatesDummyValueObject;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\Price
 */
final class PhoneNumbePriceTest extends TestCase
{
    use CreatesDummyValueObject;
    use CreatesPrice;

    public function valueObjectProvider(): Generator
    {
        $currencyName = 'dollar';
        $currencySymbol = '$';
        $currency = $this->createRealCurrency(name: $currencyName, symbol: $currencySymbol);

        $amount = 42;
        $price = $this->createRealPrice(amount: $amount, currency: $currency);

        yield 'not a price' => [
            $price,
            $this->createDummyValueObject(),
            false
        ];
        yield 'different ammount' => [
            $price,
            $this->createRealPrice(amount: 3.14, currency: $currency),
            false
        ];
        yield 'different currency' => [
            $price,
            $this->createRealPrice(
                amount: $amount,
                currency: $this->createRealCurrency(name: 'euro', symbol: '€')
            ),
            false
        ];
        yield 'same price' => [
            $price,
            $this->createRealPrice(amount: $amount, currency: $currency),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_price(PriceInterface $number, ValueObjectInterface $other, bool $expectedEquality): void
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

    public function priceFormatProvider(): Generator
    {
        $euro = $this->createRealCurrency(name: 'euro', symbol: '€');
        yield 'no decimals amount' => [42, $euro, '42.00€'];

        $dollar = $this->createRealCurrency(name: 'dollar', symbol: '$');
        yield '2 decimals rounded amount' => [42.857, $dollar, '$42.86'];
    }

    /**
     * @test
     * @dataProvider priceFormatProvider
     */
    public function shows_amount_with_2_decimals(float $amount, CurrencyInterface $currency, string $_): void
    {
        // given a price
        $price = $this->createRealPrice(amount: $amount, currency: $currency);

        // when checking its price format
        $format = (string) $price;

        $this->assertMatchesRegularExpression(
            pattern: '/^\d+\.\d{2}(?![0-9])/', // something else than a number after the 2 decimals
            string: $format,
            message: "Expected price format to contain 2 decimals"
        );
    }

    /**
     * @test
     * @dataProvider priceFormatProvider
     */
    public function shows_currency_symbol_in_string_format(float $amount, CurrencyInterface $currency, string $_): void
    {
        // given a price
        $price = $this->createRealPrice(amount: $amount, currency: $currency);

        // when checking its string format
        $format = (string) $price;

        // then it should contain its currency symbol
        $this->assertStringContainsString(
            needle: $price->currency()->symbol(),
            haystack: $format,
            message: "Expected price string-format to contain the currency symbol '{$price->currency()->symbol()}'"
        );
    }
}
