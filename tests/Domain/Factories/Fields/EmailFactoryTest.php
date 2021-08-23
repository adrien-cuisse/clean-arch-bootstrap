<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\Factories\Fields;

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

    private EmailFactoryInterface $factory;

    public function setUp(): void
    {
        $this->factory = $this->createRealEmailFactory();
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::withEmail
     * @covers ::build
     */
    public function created_email_has_given_address(): void
    {
        // given a mail address
        $emailString = 'foo@bar.org';

        // when creating an Email object from it
        $emailObject = $this->factory->withEmail(address: $emailString)->build();

        // then the created Email object should match the given mail address
        $mailAddressIsCorrect = ((string) $emailObject === $emailString);
        $this->assertTrue(
            condition: $mailAddressIsCorrect,
            message: "Created email should have the given address '{$emailString}', got '{$emailObject}'",
        );
    }
}
