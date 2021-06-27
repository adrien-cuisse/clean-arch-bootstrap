<?php

namespace Alphonse\CleanArch\Domain\Entities;

/**
 * @see UserInterface
 */
final class User extends Entity implements UserInterface
{
    public function __construct(
        private string $publicIdentifier,
        private string $privateIdentifier,
        private string $secret,
        private Iterable $roles = [],
    ) { }

    /**
     * @see UserInterface
     */
    public function getPublicIdentifier(): string
    {
        return $this->publicIdentifier;
    }

    /**
     * @see UserInterface
     */
    public function getPrivateIdentifier(): string
    {
        return $this->privateIdentifier;
    }

    /**
     * @see UserInterface
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): Iterable
    {
        return $this->roles;
    }

    /**
     * @see UserInterface
     */
    public function hasRole(RoleInterface $role): bool
    {
        foreach ($this->roles as $ownedRole) {
            if ($ownedRole->getUuid() === $role->getUuid()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see UserInterface
     */
    public function addRole(RoleInterface $role): bool
    {
        $roleIsAlreadyOwned = $this->hasRole(role: $role);

        if ($roleIsAlreadyOwned === false) {
            $this->roles[] = $role;
        }

        return $roleIsAlreadyOwned === false;
    }

    /**
     * @see UserInterface
     */
    public function removeRole(RoleInterface $role): bool
    {
        $newRoles = [];
        $roleWasRemoved = false;

        foreach ($this->roles as $ownedRole) {
            if ($ownedRole->getUuid() === $role->getUuid()) {
                $roleWasRemoved = true;
            } else {
                $newRoles[] = $ownedRole;
            }
        }

        if ($roleWasRemoved) {
            $this->roles = $newRoles;
        }

        return $roleWasRemoved;
    }
}
