<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

/**
 * A RFC 4122 compliant UUIDV4
 *
 * @see UuidInterface
 */
interface UuidV4Interface extends UuidInterface
{
    public static function fromString(string $rfcUuidString): static;
}
