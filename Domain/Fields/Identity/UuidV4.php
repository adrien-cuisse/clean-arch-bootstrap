<?php

namespace Alphonse\CleanArch\Domain\Fields\Identity;

/**
 * @see UuidV4Interface
 */
final class UuidV4 extends Uuid implements UuidV4Interface
{
     /**
     * @inheritDoc
     */
    protected function getTimeLowBytes(): array
    {
        return $this->randomBytes(4);
    }

    /**
     * @inheritDoc
     */
    protected function getTimeMidBytes(): array
    {
        return $this->randomBytes(2);
    }

    /**
     * @inheritDoc
     */
    protected function getTimeHighBytes(): array
    {
        return $this->randomBytes(2);
    }

    /**
     * @inheritDoc
     */
    protected function getClockSeqHighByte(): int
    {
        return $this->randomBytes(1)[0];
    }

    /**
     * @inheritDoc
     */
    protected function getClockSeqLowByte(): int
    {
        return $this->randomBytes(1)[0];
    }

    /**
     * @inheritDoc
     */
    protected function getNodeBytes(): array
    {
        return $this->randomBytes(6);
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): int
    {
        return 4;
    }

    /**
     * @inheritDoc
     */
    static public function fromString(string $rfcUuidString): static
    {
        return parent::fromString(rfcUuidString: $rfcUuidString);
    }
}
