<?php

namespace Alphonse\CleanArch\Tests\Domain\Fields\Identity;

use PHPUnit\Framework\TestCase;
use Alphonse\CleanArch\Domain\Traits\HasEmail;
use Alphonse\CleanArch\Domain\Traits\HasEmailInterface;
use Alphonse\CleanArch\Domain\Fields\Email\EmailInterface;

/**
 * @coversDefaultClass Alphonse\CleanArch\Domain\Traits\HasEmail
 */
final class HasEmailTest extends TestCase
{
    /**
     * @return HasEmailInterface - an object with an email
     */
    private function createInstance(EmailInterface $email): HasEmailInterface
    {
        return new class($email) implements HasEmailInterface {
            use HasEmail;

            public function __construct(private EmailInterface $email) { }
        };
    }

    private function createEmail(): EmailInterface
    {
        return new class implements EmailInterface {
            public function __toString()
            {
                return 'email';
            }
        };
    }

    /**
     * @test
     * @covers ::getEmail
     */
    public function returns_email(): void
    {
        // given a new Email and an object having it
        $email = $this->createEmail();
        $owner = $this->createInstance(email: $email);

        // when requesting the object's identity
        $storedEmail = $owner->getEmail();

        // then it should be the one given at construction
        $this->assertSame(
            expected: $email,
            actual: $storedEmail,
            message: "The object returned the wrong email",
        );
    }
}
