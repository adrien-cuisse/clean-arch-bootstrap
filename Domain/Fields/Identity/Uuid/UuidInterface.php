<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;

/**
 * A RFC 4122 compliant UUID
 *
 * @see {@link https://datatracker.ietf.org/doc/html/rfc4122#section-4.1.5}
 */
interface UuidInterface extends IdentityInterface
{
    /**
     * @return int - the version of the UUID
     */
    public function getVersion(): int;

    /**
     * @return string- the variant of the UUID
     */
    public function getVariant(): string;

    /**
     * @return string - RFC 4122 compliant UUID representation (eg, 01234567-89ab-cdef-0123-456789abcdef)
     */
    public function toRfcUuidString(): string;
}
