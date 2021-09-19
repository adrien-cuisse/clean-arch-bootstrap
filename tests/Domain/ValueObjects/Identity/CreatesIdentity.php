<?php

namespace Alphonse\CleanArchBootstrap\Tests\Domain\ValueObjects\Identity;

use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\Uuid\UuidV4;
use Alphonse\CleanArchBootstrap\Domain\ValueObjects\Identity\IdentityInterface;

trait CreatesIdentity
{
    private function createRealIdentity(): IdentityInterface
    {
        return new UuidV4;
    }

    private function createFakeIdentity(): IdentityInterface
    {
        $identity = $this->getMock(IdentityInterface::class);
        $identity->method('__toString')->willReturn('unique identity');

        return $identity;
    }
}
