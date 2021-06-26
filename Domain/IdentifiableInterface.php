<?php

namespace Alphonse\CleanArch\Domain;

/**
 * Some objects with unique identity
 */
interface IdentifiableInterface
{
    /**
     * Returns an unique identifier
     *
     * @return string - a UUID
     */
    public function getUuid(): string;
}
