<?php

namespace Alphonse\CleanArch\Domain;

/**
 * @see IdentifiableInterface
 */
trait Identifiable
{
    private UuidInterface $uuid;

    final public function getUuid(): string
    {
        if (isset($this->uuid) === false) {
            $this->uuid = new UuidV4;
        }

        return $this->uuid->toRfcUuidString();
    }
}
