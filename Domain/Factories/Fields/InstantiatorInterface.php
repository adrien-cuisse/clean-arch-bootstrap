<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

interface InstantiatorInterface
{
    public function instantiate(string $fullyQualifiedClassName): mixed;

    /**
     * @param string $name - the name of the argument to assign
     * @param mixed $value - the value to assign to the argument
     *
     * @return static - the instantiator with the added argument
     */
    public function assignConstructorArgument(string $name, mixed $value): static;

    /**
     * @return array<string,mixed> - map of so far assigned arguments name to arguments value
     */
    public function assignedConstructorArguments(): array;

    /**
     * @param string $name - the name of the argument to check for assignment
     *
     * @return bool - true if the argument has been assigned, false otherwise
     */
    public function hasAssignedConstructorArgument(string $name): bool;
}
