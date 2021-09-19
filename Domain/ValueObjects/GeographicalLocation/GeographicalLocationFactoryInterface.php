<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\FactoryInterface;

interface GeographicalLocationFactoryInterface extends FactoryInterface
{
    public function withLatitude(float $latitude): self;

    public function withLongitude(float $longitude): self;

    public function build(): GeographicalLocationInterface;
}
