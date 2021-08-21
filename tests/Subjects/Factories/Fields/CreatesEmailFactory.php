<?php

namespace Alphonse\CleanArchBootstrap\Tests\Subjects\Factories\Fields;

use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\EmailFactory;
use Alphonse\CleanArchBootstrap\Domain\Factories\Fields\EmailFactoryInterface;
use Alphonse\CleanArchBootstrap\Tests\Subjects\Fields\CreatesEmail;

trait CreatesEmailFactory
{
    use CreatesEmail;

    private function createRealEmailFactory(): EmailFactoryInterface
    {
        return new EmailFactory;
    }

    private function createFakeEmailFactory(): EmailFactoryInterface
    {
        $factory = $this->getMockBuilder(EmailFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory->method('withEmail')->willReturn($factory);
        $factory->method('build')->willReturn($this->createFakeEmail());

        return $factory;
    }
}
