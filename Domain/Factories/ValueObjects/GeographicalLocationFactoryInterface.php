<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\ValueObjects;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation\GeographicalLocationInterface;

interface GeographicalLocationFactoryInterface extends FactoryInterface
{
    public function withLatitude(float $latitude): self;

    public function withLongitude(float $longitude): self;

    public function build(): GeographicalLocationInterface;
}
