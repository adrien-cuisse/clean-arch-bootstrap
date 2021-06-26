<?php

namespace Alphonse\CleanArch\Tests\Domain;

use Alphonse\CleanArch\Domain\RoleInterface;
use Alphonse\CleanArch\Domain\Role;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Role
 */
final class RoleTest extends TestCase
{
    /**
     * @return RoleInterface - an instance to test
     */
    public function createInstance(string $name = '', int $level = 0): RoleInterface
    {
        return new Role(name: $name, level: $level);
    }

    /**
     * @test
     * @covers ::getName
     */
    public function returns_name(): void
    {
        // given a new role
        $role = $this->createInstance(name: 'some role');

        // when getting its name
        $name = $role->getName();

        // then it should the one used at creation
        $this->assertEquals(
            expected: 'some role',
            actual: $name,
            message: "Role didn't return the name used at creation, expected 'some role', got {$name}",
        );
    }

    /**
     * @test
     * @covers ::getLevel
     */
    public function returns_level(): void
    {
        // given a new role
        $role = $this->createInstance(level: 42);

        // when getting its level
        $level = $role->getLevel();

        // then it should the one used at creation
        $this->assertEquals(
            expected: 42,
            actual: $level,
            message: "Role didn't return the level used at creation, expected '42', got {$level}",
        );
    }
}
