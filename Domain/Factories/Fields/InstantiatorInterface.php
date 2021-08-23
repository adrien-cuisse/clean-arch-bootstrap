<?php

namespace Alphonse\CleanArchBootstrap\Domain\Factories\Fields;

interface InstantiatorInterface
{
    /**
     * @param string $class - fully-qualified class name to create an instance from
     *
     * @return mixed - an instance of the specified class
     */
    public function createInstance(string $class): mixed;

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
    public function getAssignedConstructorArguments(): array;

    /**
     * @param string $name - the name of the argument to check for assignment
     *
     * @return bool - true if the argument has been assigned, false otherwise
     */
    public function hasAssignedConstructorArgument(string $name): bool;
}
