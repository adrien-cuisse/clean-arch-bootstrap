<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Price;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Currency\CreatesCurrency;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\PriceFactory
 */
final class PriceFactoryTest extends TestCase
{
    use CreatesCurrency;
    use CreatesPriceFactory;

    private PriceFactoryInterface $factory;

    public function setUp(): void
    {
        $currency = $this->createRealCurrency(
            name: '',
            symbol: ''
        );

        $this->factory = $this->createRealCurrencyFactory()
            ->withAmount(0)
            ->withCurrency($currency);
    }

    /**
     * @test
     */
    public function created_price_has_given_amount(): void
    {
        // given an amount
        $amount = 0.618;

        // when creating a price from it
        $price = $this->factory->withAmount($amount)->build();

        // then the created price's amount should match the given amount
        $this->assertSame(
            expected: $amount,
            actual: $price->amount(),
            message: "Created Price should have the given amount '{$amount}', got '{$price->amount()}'",
        );
    }

    /**
     * @test
     */
    public function created_price_has_given_currency(): void
    {
        // given a currency
        $currency = $this->createRealCurrency(name: '', symbol: '');

        // when creating a price from it
        $price = $this->factory->withCurrency($currency)->build();

        // then the created price's currency should match the given currency
        $this->assertSame(
            expected: $currency,
            actual: $price->currency(),
            message: "Created Price should have the given currency '{$currency}', got '{$price->currency()}'",
        );
    }
}
