<?php

namespace Alphonse\CleanArch\Domain;

/**
 * @see RoleInterface
 */
final class Role extends Entity implements RoleInterface
{
    public function __construct(
        private string $name,
        private int $level,
    ) { }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
