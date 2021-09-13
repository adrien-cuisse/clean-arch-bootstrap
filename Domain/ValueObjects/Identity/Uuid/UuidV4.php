<?php

namespace Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid;

/**
 * @see UuidV4Interface
 */
final class UuidV4 extends Uuid implements UuidV4Interface
{
    public function __construct()
    {
        parent::__construct(
            4,
            $this->randomBytes(4),
            $this->randomBytes(2),
            $this->randomBytes(2),
            $this->randomByte(),
            $this->randomByte(),
            $this->randomBytes(6),
        );
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $rfcUuidString): static
    {
        return parent::fromString($rfcUuidString);
    }
}
