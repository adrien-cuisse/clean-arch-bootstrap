<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Fields;

use Alphonse\CleanArchBootstrap\Domain\Fields\Email\Email;
use Alphonse\CleanArchBootstrap\Domain\Fields\Email\EmailInterface;

trait CreatesEmail
{
    private function createRealEmail(string $email = 'foo@bar.org'): EmailInterface
    {
        return new Email(email: $email);
    }

    private function createFakeEmail(): EmailInterface
    {
        $email = $this->getMock(EmailInterface::class);
        $email->method('__toString')->willReturn('email address');

        return $email;
    }
}
