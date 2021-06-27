<?php

namespace Alphonse\CleanArch\Domain\Entities;

/**
 * A role users of the application may own
 */
interface RoleInterface extends EntityInterface
{
    public function getName(): string;

    /**
     * In case of a role hierarchy, an higher level grants lower level privileges
     * If you're not using an hierarychy, you may remove this
     */
    public function getLevel(): int;
}
