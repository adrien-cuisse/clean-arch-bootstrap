<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity;

/**
 * A RFC 4122 compliant UUID
 *
 * @see {@link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.5}
 */
interface UuidInterface
{
    /**
     * @return int - the version of the UUID, must be from 1 to 5
     */
    public function getVersion(): int;

    /**
     * @return string - RFC 4122 compliant UUID representation (eg, 01234567-89ab-cdef-0123-456789abcdef)
     */
    public function toRfcUuidString(): string;
}
