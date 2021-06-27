<?php

namespace Alphonse\CleanArch\Domain\Entities;

/**
 * An user of the application
 */
interface UserInterface extends EntityInterface
{
    /**
     * @return string - a public identifier visible by anyone (eg, a nickname)
     */
    public function getPublicIdentifier(): string;

    /**
     * @return string - a private identifier used to authenticate the user, (eg, a mail address or a phone number)
     */
    public function getPrivateIdentifier(): string;

    /**
     * @return string - some secret information to check the identity of the user (eg, a password)
     */
    public function getSecret(): string;

    /**
     * @return RoleInterface[]
     */
    public function getRoles(): Iterable;

    public function hasRole(RoleInterface $role): bool;

    /**
     * @return bool - true if the role was added, false if user already owned it
     */
    public function addRole(RoleInterface $role): bool;

    /**
     * @return bool - true if the role was removed, false if the user didn't own it
     */
    public function removeRole(RoleInterface $role): bool;
}
