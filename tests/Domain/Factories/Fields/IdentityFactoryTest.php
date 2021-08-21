<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesIdentityFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\IdentityFactory
 * @uses Alphonse\CleanArchBootstrap\Domain\Fields\Identity\Uuid\UuidV4<extended>
 */
final class IdentityFactoryTest extends TestCase
{
    use CreatesIdentityFactory;

    /**
     * @test
     * @covers ::build
     */
    public function creates_new_identity_if_not_provided(): void
    {
        // given a factory
        $factory = $this->createRealIdentityFactory();

        // when asked to build without giving an identity-string
        $identity = $factory->build();

        // then identity shouldn't be empty
        $this->assertSame(
            expected: 36,
            actual: strlen(string: (string) $identity),
            message: 'Identity factory should create a new identity if no identity-string was provided'
        );
    }

    /**
     * @test
     * @covers ::withIdentity
     * @covers ::build
     */
    public function creates_identity_from_string(): void
    {
        // given a factory
        $factory = $this->createRealIdentityFactory();

        // when asked to create a new Identity from an identity-string
        $identity = $factory->withIdentity(identity: '00000000-0000-0000-0000-000000000000')->build();

        // then Identity object should have the given string-representation
        $this->assertSame(
            expected: '00000000-0000-0000-0000-000000000000',
            actual: (string) $identity,
            message: "Identity factory didn't create an Identity from the identity-string"
        );
    }
}
