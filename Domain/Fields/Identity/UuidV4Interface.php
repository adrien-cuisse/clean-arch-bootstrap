<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity;

/**
 * A RFC 4122 compliant UUIDV4
 * 
 * @see UuidInterface
 */
interface UuidV4Interface
{
    public static function fromString(string $rfcUuidString): static;
}
