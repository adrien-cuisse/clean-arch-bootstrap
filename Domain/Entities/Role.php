<?php

namespace Alphonse\CleanArch\Domain\Entities;

/**
 * @see RoleInterface
 */
final class Role extends Entity implements RoleInterface
{
    public function __construct(
        private string $name,
        private int $level,
    ) { }

    /**
     * @see RoleInterface
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @see RoleInterface
     */
    public function getLevel(): int
    {
        return $this->level;
    }
}
