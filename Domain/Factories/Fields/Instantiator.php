<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

final class Instantiator implements InstantiatorInterface
{
    private array $constructorArguments = [];

    public function createInstance(string $class): mixed
    {
        return new $class(...$this->constructorArguments);
    }

    public function assignConstructorArgument(string $name, mixed $value): static
    {
        $instantiator = clone $this;
        $instantiator->constructorArguments[$name] = $value;

        return $instantiator;
    }

    public function getAssignedConstructorArguments(): array
    {
        return $this->constructorArguments;
    }

    public function hasAssignedConstructorArgument(string $name): bool
    {
        return isset($this->constructorArguments[$name]);
    }
}
