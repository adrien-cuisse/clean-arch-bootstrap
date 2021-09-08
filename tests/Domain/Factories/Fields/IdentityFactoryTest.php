<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\IdentityFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesIdentityFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\IdentityFactory
 * @uses Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\UuidV4<extended>
 */
final class IdentityFactoryTest extends TestCase
{
    use CreatesIdentityFactory;

    private IdentityFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealIdentityFactory();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::build
     */
    public function creates_new_identity_if_not_provided(): void
    {
        // given no identity-string

        // when asked to build an Identity object
        $identity = $this->factory->build();

        // then the Identity shouldn't be empty
        $identityStringLength = strlen($identity);
        $this->assertGreaterThan(
            expected: 0,
            actual: $identityStringLength,
            message: 'Identity factory should create a new identity if no identity-string was provided',
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::withIdentity
     * @covers ::build
     */
    public function creates_identity_from_string(): void
    {
        // given an identity-string
        $identityString = '00000000-0000-0000-0000-000000000000';

        // when creating a new Identity object from it
        $identityObject = $this->factory->withIdentity($identityString)->build();

        // then the created Identity object should match the given identity-string
        $this->assertSame(
            expected: $identityString,
            actual: (string) $identityObject,
            message: "Identity factory didn't create an Identity from the identity-string '{$identityString}', got '{$identityObject}'",
        );
    }
}
