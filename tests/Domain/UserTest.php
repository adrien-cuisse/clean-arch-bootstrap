<?php

namespace Alphonse\CleanArch\Tests\Domain;

use Alphonse\CleanArch\Domain\RoleInterface;
use Alphonse\CleanArch\Domain\Role;
use Alphonse\CleanArch\Domain\UserInterface;
use Alphonse\CleanArch\Domain\User;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\User
 */
final class UserTest extends TestCase
{
    /**
     * @return UserInterface - an instance to test
     */
    private function createInstance(
        string $username = '',
        string $email = '',
        string $password = '',
        Iterable $roles = []
    ) {
        return new User(
            publicIdentifier: $username,
            privateIdentifier: $email,
            secret: $password,
            roles: $roles,
        );
    }

    /**
     * @return RoleInterface - a user's role
     */
    private function createRole(): RoleInterface
    {
        return new Role(name: '', level: 0);
    }

    /**
     * @test
     * @covers ::getPublicIdentifier
     */
    public function returns_public_identifier(): void
    {
        // given a new User
        $user = $this->createInstance(username: 'username');

        // when getting its public identifier
        $identifier = $user->getPublicIdentifier();

        // then it should the one used at creation
        $this->assertSame(
            expected: 'username',
            actual: $identifier,
            message: "User didn't return the public identifier used at creation, expected 'username', got {$identifier}",
        );
    }

    /**
     * @test
     * @covers ::getPrivateIdentifier
     */
    public function returns_private_identifier(): void
    {
        // given a new User
        $user = $this->createInstance(email: 'some@email.com');

        // when getting its private identifier
        $identifier = $user->getPrivateIdentifier();

        // then it should the one used at creation
        $this->assertSame(
            expected: 'some@email.com',
            actual: $identifier,
            message: "User didn't return the private identifier used at creation, expected 'some@email.com', got {$identifier}",
        );
    }

    /**
     * @test
     * @covers ::getSecret
     */
    public function returns_secret(): void
    {
        // given a new User
        $user = $this->createInstance(password: 'some secret');

        // when getting its secret
        $secret = $user->getSecret();

        // then it should the one used at creation
        $this->assertSame(
            expected: 'some secret',
            actual: $secret,
            message: "User didn't return the secret used at creation, expected 'some secret', got {$secret}",
        );
    }

    /**
     * @test
     * @covers ::getSecret
     */
    public function returns_roles(): void
    {
        // given a new User with a role
        $role = $this->createRole();
        $user = $this->createInstance(roles: [$role]);

        // when getting its roles
        $roles = $user->getRoles();

        // then they shoud be the ones used at creation
        $this->assertSame(
            expected: [$role],
            actual: $roles,
            message: "User didn't return the roles used at creation",
        );
    }

    public function roleProvider(): array
    {
        return [
            [$this->createRole(), true],
            [$this->createRole(), false],
        ];
    }

    /**
     * @test
     * @covers ::hasRole
     * @dataProvider roleProvider
     */
    public function detects_role(RoleInterface $role, bool $giveRole): void
    {
        // given a new User with specified roles
        $user = $this->createInstance(roles: $giveRole ? [$role] : []);

        // when checking if it has the given role
        $hasRole = $user->hasRole(role: $role);

        $this->assertSame(
            expected: $giveRole,
            actual: $hasRole,
            message: "User didn't detect it has roles given at creation",
        );
    }

    /**
     * @test
     * @covers ::addRole
     * @dataProvider roleProvider
     */
    public function adds_role(RoleInterface $role, bool $alreadyHasTheRole): void
    {
        // given a new User with specified roles
        $user = $this->createInstance(roles: $alreadyHasTheRole ? [$role] : []);

        // when adding a role to the user
        $roleWasAdded = $user->addRole(role: $role);

        // then it should be added only if the user didn't already own it
        $this->assertSame(
            expected: $alreadyHasTheRole === false,
            actual: $roleWasAdded,
            message: "Role should be added if the user didn't already own it",
        );
    }

    /**
     * @test
     * @covers ::removeRole
     * @dataProvider roleProvider
     */
    public function removes_role(RoleInterface $role, bool $ownsTheRole): void
    {
        // given a new User with specified roles
        $user = $this->createInstance(roles: $ownsTheRole ? [$role] : []);

        // when adding a role to the user
        $roleWasRemoved = $user->removeRole(role: $role);

        // then it should get removed only if the user owned it
        $this->assertSame(
            expected: $ownsTheRole,
            actual: $roleWasRemoved,
            message: "Role should be removed if the user owned it",
        );
    }
}
