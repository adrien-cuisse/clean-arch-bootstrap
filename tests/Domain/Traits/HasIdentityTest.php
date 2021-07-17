<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Traits\HasIdentity;
use Alphonse\CleanArch\Domain\Traits\HasIdentityInterface;
use Alphonse\CleanArch\Domain\Fields\Identity\IdentityInterface;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Traits\HasIdentity
 */
final class HasIdentityTest extends TestCase
{
    /**
     * @return HasIdentityInterface - an object with an identity
     */
    private function createInstance(IdentityInterface $identity): HasIdentityInterface
    {
        return new class($identity) implements HasIdentityInterface {
            use HasIdentity;

            public function __construct(private IdentityInterface $identity) { }
        };
    }

    private function createIdentity(): IdentityInterface
    {
        return new class implements IdentityInterface {
            public function __toString()
            {
                return 'identity';
            }
        };
    }

    /**
     * @test
     * @covers ::getIdentity
     */
    public function returns_identity(): void
    {
        // given a new Uuid and an object having it
        $identity = $this->createIdentity();
        $owner = $this->createInstance(identity: $identity);

        // when requesting the object's identity
        $storedIdentity = $owner->getIdentity();

        // then it should be the one given at construction
        $this->assertSame(
            expected: $identity,
            actual: $storedIdentity,
            message: "The object returned the wrong identity",
        );
    }
}
