<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\GeographicalLocation;

interface LocalizableInterface
{
    public function location(): GeographicalLocationInterface;
}
