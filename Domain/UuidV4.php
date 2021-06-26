<?php

namespace Alphonse\CleanArch\Domain;

/**
 * A RFC 4122 compliant UUID version 4
 */
final class UuidV4 extends Uuid
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
}
