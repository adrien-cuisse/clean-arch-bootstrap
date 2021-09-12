<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\GeographicalLocation\GeographicalLocationInterface;

interface GeographicalLocationFactoryInterface extends FactoryInterface
{
    public function withLatitude(float $latitude): self;

    public function withLongitude(float $longitude): self;

    public function build(): GeographicalLocationInterface;
}
