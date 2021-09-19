<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Price;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\NegativePriceException;

/**
 * @covers Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price\NegativePriceException
 */
final class NegativePriceExceptionTest extends TestCase
{
    use CreatesPrice;

    /**
     * @test
     */
    public function shows_price_in_message(): void
    {
        // given some negative price
        $invalidPrice = -1;

        // when using it to create an exception
        $exception = new NegativePriceException($invalidPrice);

        // then its error message should contain the invalid price
        $this->assertStringContainsString(
            needle: $invalidPrice,
            haystack: $exception->getMessage(),
            message: "The error message should contain the invalid price '{$invalidPrice}'"
        );
    }
}
