<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Fields\Identity;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArchBootstrap\Domain\Traits\Identifiable;
use Alphonse\CleanArchBootstrap\Domain\Traits\IdentifiableInterface;
use Alphonse\CleanArchBootstrap\Domain\Fields\Identity\IdentityInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesIdentity;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Traits\Identifiable
 */
final class IdentifiableTest extends TestCase
{
    use CreatesIdentity;

    private IdentityInterface $identity;

    public function setUp(): void
    {
        $this->identity = $this->createRealIdentity();
    }

    /**
     * @return IdentifiableInterface - an object with an identity
     */
    private function createInstance(IdentityInterface $identity): IdentifiableInterface
    {
        return new class($identity) implements IdentifiableInterface {
            use Identifiable;

            public function __construct(private IdentityInterface $identity)
            {
            }
        };
    }

    /**
     * @test
     * @covers ::identity
     */
    public function returns_identity(): void
    {
        // given an object having an identity
        $owner = $this->createInstance(identity: $this->identity);

        // when requesting the object's identity
        $storedIdentity = $owner->identity();

        // then it should be the one given at construction
        $this->assertSame(
            expected: $this->identity,
            actual: $storedIdentity,
            message: "The object returned the wrong identity, expected '{$this->identity}', got '{$storedIdentity}'",
        );
    }
}
