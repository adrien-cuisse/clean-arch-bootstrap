<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\EmailFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\EmailFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields\CreatesEmailFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Alphonse\CleanArchBootstrap\Domain\Factories\Fields\EmailFactory
 * @uses Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email
 */
final class EmailFactoryTest extends TestCase
{
    use CreatesEmailFactory;

    /**
     * @test
     * @covers ::withEmail
     * @covers ::build
     */
    public function created_email_has_given_address(): void
    {
        // given a new factory
        $factory = $this->createRealEmailFactory();

        // when adding a new property to the target object
        $email = $factory->withEmail(address: 'foo@bar.org')->build();

        // then both instances should be different
        $this->assertSame(
            expected: 'foo@bar.org',
            actual: (string) $email,
            message: "Created email should have the given address"
        );
    }
}
