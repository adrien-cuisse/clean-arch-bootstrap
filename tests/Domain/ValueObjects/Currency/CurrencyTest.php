<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Currency;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\ValueObjectInterface;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\CreatesDummyValueObject;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\Currency
 */
final class CurrencyTest extends TestCase
{
    use CreatesDummyValueObject;
    use CreatesCurrency;

    public function valueObjectProvider(): Generator
    {
        $name = 'dollar';
        $symbol = '$';

        $currency = $this->createRealCurrency(name: $name, symbol: $symbol);

        yield 'not a currency' => [
            $currency,
            $this->createDummyValueObject(),
            false
        ];
        yield 'different name' => [
            $currency,
            $this->createRealCurrency(name: 'something else', symbol: $symbol),
            false
        ];
        yield 'different symbol' => [
            $currency,
            $this->createRealCurrency(name: $name, symbol: 'something else'),
            false
        ];
        yield 'same currency' => [
            $currency,
            $this->createRealCurrency(name: $name, symbol: $symbol),
            true
        ];
    }

    /**
     * @test
     * @dataProvider valueObjectProvider
     */
    public function matches_same_currency(CurrencyInterface $currency, ValueObjectInterface $other, bool $expectedEquality): void
    {
        // given a value object to compare with

        // when comparing the 2 instances
        $areSameValue = $currency->equals($other);

        // when it should match the expected equality
        $this->assertSame(
            expected: $expectedEquality,
            actual: $areSameValue,
        );
    }

    public function currencyProvider(): Generator
    {
        yield 'euro' => ['euro', '€'];
        yield 'dollar' => ['dollar', '$'];
    }

    /**
     * @test
     * @dataProvider currencyProvider
     */
    public function formats_as_string_to_symbol(string $_, string $symbol): void
    {
        // given a currency
        $currency = $this->createRealCurrency(symbol: $symbol);

        // when checking its string format
        $format = (string) $currency;

        // then it should be its symbol
        $this->assertSame(
            expected: $currency->symbol(),
            actual: $format,
            message: "Currency should show as symbol '{$currency->symbol()}', got '{$format}'"
        );
    }
}
