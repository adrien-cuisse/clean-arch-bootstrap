<?php

namespace Alphonse\CleanArch\Domain;

/**
 * @see IdentifiableInterface
 */
trait Identifiable
{
    private UuidInterface $uuid;

    public function __construct()
    {
        $this->uuid = new UuidV4;
    }

    final public function getUuid(): string
    {
        return $this->uuid->toRfcUuidString();
    }
}
