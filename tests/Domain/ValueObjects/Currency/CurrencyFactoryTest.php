<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Currency;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyFactoryInterface;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Currency\CurrencyFactory
 */
final class CurrencyFactoryTest extends TestCase
{
    use CreatesCurrencyFactory;

    private CurrencyFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealCurrencyFactory();
    }

    /**
     * @test
     */
    public function created_currency_has_given_name(): void
    {
        // given a currency name
        $name = 'yuán';

        // when creating a currency from it
        $currency = $this->factory->withName($name)->withSymbol('')->build();

        // then the created currency's name should match the given name
        $this->assertSame(
            expected: $name,
            actual: $currency->name(),
            message: "Created Currency should have the given name '{$name}', got '{$currency->name()}'",
        );
    }

    /**
     * @test
     */
    public function created_currency_has_given_symbol(): void
    {
        // given a currency symbol
        $symbol = '¥';

        // when creating a currency from it
        $currency = $this->factory->withName('')->withSymbol($symbol)->build();

        // then the created currency's name should match the given name
        $this->assertSame(
            expected: $symbol,
            actual: $currency->symbol(),
            message: "Created Currency should have the given symbol '{$symbol}', got '{$currency->symbol()}'",
        );
    }
}
