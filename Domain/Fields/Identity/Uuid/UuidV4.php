<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity\Uuid;

/**
 * @see UuidV4Interface
 */
final class UuidV4 extends Uuid implements UuidV4Interface
{
    public function __construct()
    {
        parent::__construct(
            version: 4,
            timeLowBytes: $this->randomBytes(4),
            timeMidBytes: $this->randomBytes(2),
            timeHighBytes: $this->randomBytes(2),
            clockSeqHighByte: $this->randomByte(),
            clockSeqLowByte: $this->randomByte(),
            nodeBytes: $this->randomBytes(6),
        );
    }

    /**
     * @inheritDoc
     */
    static public function fromString(string $rfcUuidString): static
    {
        return parent::fromString(rfcUuidString: $rfcUuidString);
    }
}
