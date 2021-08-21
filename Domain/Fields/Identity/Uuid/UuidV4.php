<?php

namespace Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid;

/**
 * @see UuidV4Interface
 */
final class UuidV4 extends Uuid implements UuidV4Interface
{
    public function __construct()
    {
        parent::__construct(
            version: 4,
            timestampLowBytes: $this->randomBytes(4),
            timestampMidBytes: $this->randomBytes(2),
            timestampHighBytes: $this->randomBytes(2),
            clockSequenceHighByte: $this->randomByte(),
            clockSequenceLowByte: $this->randomByte(),
            nodeBytes: $this->randomBytes(6),
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $rfcUuidString): static
    {
        return parent::fromString(rfcUuidString: $rfcUuidString);
    }
}
