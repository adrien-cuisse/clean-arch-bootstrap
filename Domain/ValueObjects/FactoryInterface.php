<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects;

interface FactoryInterface
{
    public function build(): mixed;
}
