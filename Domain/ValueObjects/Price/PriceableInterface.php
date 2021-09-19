<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Price;

interface PriceableInterface
{
    public function price(): PriceInterface;
}
