<?php

namespace Alphonse\CleanArch\Domain;

interface UuidV4Interface
{
    public static function fromString(string $rfcUuidString): static;
}
